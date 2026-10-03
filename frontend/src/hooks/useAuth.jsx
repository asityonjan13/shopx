import { createContext, useContext, useEffect, useMemo, useState } from 'react';
import { api, payload } from '../services/api';

const AuthContext = createContext(null);

export function AuthProvider({ children }) {
  const [user, setUser] = useState(null);
  const [admin, setAdmin] = useState(null);
  const [ready, setReady] = useState(false);

  useEffect(() => {
    let cancelled = false;

    async function boot() {
      const jobs = [];
      if (localStorage.getItem('shopx_token')) {
        jobs.push(
          api.get('/auth/me').then((response) => {
            if (!cancelled) setUser(payload(response));
          }).catch(() => localStorage.removeItem('shopx_token'))
        );
      }
      if (localStorage.getItem('shopx_admin_token')) {
        jobs.push(
          api.get('/admin/me').then((response) => {
            if (!cancelled) setAdmin(payload(response));
          }).catch(() => localStorage.removeItem('shopx_admin_token'))
        );
      }
      await Promise.all(jobs);
      if (!cancelled) setReady(true);
    }

    boot();
    return () => {
      cancelled = true;
    };
  }, []);

  const value = useMemo(() => ({
    user,
    admin,
    ready,
    setUser,
    setAdmin,
    async login(credentials) {
      const response = await api.post('/auth/login', credentials);
      localStorage.setItem('shopx_token', response.data.token);
      setUser(response.data.data);
      return response.data.data;
    },
    async register(form) {
      const response = await api.post('/auth/register', form);
      localStorage.setItem('shopx_token', response.data.token);
      setUser(response.data.data);
      return response.data.data;
    },
    async logout() {
      try {
        await api.post('/auth/logout');
      } catch {
        // The token is already gone or expired.
      }
      localStorage.removeItem('shopx_token');
      setUser(null);
    },
    async loginAdmin(credentials) {
      const response = await api.post('/admin/login', credentials);
      localStorage.setItem('shopx_admin_token', response.data.token);
      setAdmin(response.data.data);
      return response.data.data;
    },
    async logoutAdmin() {
      try {
        await api.post('/admin/logout');
      } catch {
        // Ignore an already-expired admin token.
      }
      localStorage.removeItem('shopx_admin_token');
      setAdmin(null);
    },
  }), [user, admin, ready]);

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
}

export function useAuth() {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error('useAuth must be used inside AuthProvider');
  }
  return context;
}
