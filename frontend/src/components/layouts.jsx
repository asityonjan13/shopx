import { useEffect, useState } from 'react';
import { NavLink, Outlet, useLocation, useNavigate } from 'react-router-dom';
import { api, payload } from '../services/api';
import { useAuth } from '../hooks/useAuth';
import { Success } from './ui';

function NavItem({ to, children }) {
  return (
    <NavLink
      to={to}
      className={({ isActive }) => `rounded-full px-3 py-2 text-sm ${isActive ? 'bg-ink text-white' : 'text-ink hover:bg-black/5'}`}
    >
      {children}
    </NavLink>
  );
}

export function StorefrontLayout() {
  const { user, logout } = useAuth();
  const [count, setCount] = useState(0);
  const location = useLocation();
  const navigate = useNavigate();

  useEffect(() => {
    if (!user) {
      setCount(0);
      return;
    }
    api.get('/cart').then((response) => setCount(payload(response)?.count || 0)).catch(() => setCount(0));
  }, [user, location.pathname]);

  return (
    <div className="min-h-screen">
      <header className="sticky top-0 z-30 border-b border-line bg-paper/90 backdrop-blur">
        <div className="mx-auto flex max-w-6xl items-center gap-3 px-4 py-3">
          <NavLink to="/" className="font-display text-2xl">ShopX</NavLink>
          <nav className="ml-4 hidden items-center gap-1 md:flex">
            <NavItem to="/shop">Shop</NavItem>
            <NavItem to="/track">Track order</NavItem>
          </nav>
          <div className="ml-auto flex items-center gap-2">
            <NavLink to="/cart" className="rounded-full border border-line bg-card px-3 py-2 text-sm">Cart {count ? `(${count})` : ''}</NavLink>
            {user ? (
              <>
                <NavLink to={user.user_type === 'vendor' ? '/vendor' : '/account'} className="hidden rounded-full px-3 py-2 text-sm sm:inline">{user.name.split(' ')[0]}</NavLink>
                <button type="button" className="text-sm text-muted" onClick={async () => { await logout(); navigate('/'); }}>Sign out</button>
              </>
            ) : (
              <NavLink to="/login" className="rounded-full bg-ink px-3 py-2 text-sm text-white">Sign in</NavLink>
            )}
          </div>
        </div>
      </header>
      <main className="mx-auto max-w-6xl px-4 py-8">
        <Success>{location.state?.notice}</Success>
        <div className={location.state?.notice ? 'mt-4' : ''}>
          <Outlet />
        </div>
      </main>
      <footer className="border-t border-line">
        <div className="mx-auto flex max-w-6xl flex-col gap-2 px-4 py-8 text-sm text-muted sm:flex-row sm:justify-between">
          <p>ShopX, goods from independent sellers.</p>
          <NavLink to="/admin/login" className="hover:text-ink">Admin</NavLink>
        </div>
      </footer>
    </div>
  );
}

function SideNav({ title, links, onLogout }) {
  const location = useLocation();
  return (
    <div className="min-h-screen bg-paper md:grid md:grid-cols-[240px_1fr]">
      <aside className="border-b border-line bg-card md:min-h-screen md:border-b-0 md:border-r">
        <div className="flex items-center justify-between px-4 py-4 md:block">
          <NavLink to="/" className="font-display text-2xl">ShopX</NavLink>
          <p className="text-xs uppercase tracking-wide text-muted md:mt-1">{title}</p>
        </div>
        <nav className="flex gap-1 overflow-auto px-3 pb-3 md:flex-col">
          {links.map((link) => (
            <NavLink
              key={link.to}
              to={link.to}
              end={link.end}
              className={({ isActive }) => `whitespace-nowrap rounded-full px-3 py-2 text-sm ${isActive ? 'bg-ink text-white' : 'hover:bg-paper'}`}
            >
              {link.label}
            </NavLink>
          ))}
          <button type="button" onClick={onLogout} className="rounded-full px-3 py-2 text-left text-sm text-muted">Sign out</button>
        </nav>
      </aside>
      <main className="px-4 py-6 md:px-8">
        <Success>{location.state?.notice}</Success>
        <div className={location.state?.notice ? 'mt-4' : ''}>
          <Outlet />
        </div>
      </main>
    </div>
  );
}

export function AccountLayout() {
  const { user, logout } = useAuth();
  const navigate = useNavigate();
  const links = [
    { to: '/account', label: 'Overview', end: true },
    { to: '/account/orders', label: 'Orders' },
    { to: '/account/wishlist', label: 'Wishlist' },
    { to: '/account/addresses', label: 'Addresses' },
    { to: '/account/profile', label: 'Profile' },
  ];
  if (user?.user_type === 'vendor') {
    links.push({ to: '/account/kyc', label: 'Identity' });
    links.push({ to: '/vendor', label: 'Vendor desk' });
  }
  return (
    <SideNav
      title="Your account"
      links={links}
      onLogout={async () => { await logout(); navigate('/'); }}
    />
  );
}

export function VendorLayout() {
  const { logout } = useAuth();
  const navigate = useNavigate();
  return (
    <SideNav
      title="Vendor"
      links={[
        { to: '/vendor', label: 'Overview', end: true },
        { to: '/vendor/store', label: 'Store profile' },
        { to: '/vendor/products', label: 'Products' },
        { to: '/account', label: 'Customer account' },
      ]}
      onLogout={async () => { await logout(); navigate('/'); }}
    />
  );
}

export function AdminLayout() {
  const { logoutAdmin } = useAuth();
  const navigate = useNavigate();
  return (
    <SideNav
      title="Admin"
      links={[
        { to: '/admin', label: 'Overview', end: true },
        { to: '/admin/categories', label: 'Categories' },
        { to: '/admin/kyc', label: 'KYC' },
        { to: '/admin/roles', label: 'Roles' },
        { to: '/admin/staff', label: 'Staff' },
        { to: '/admin/settings', label: 'Settings' },
        { to: '/admin/profile', label: 'Profile' },
      ]}
      onLogout={async () => { await logoutAdmin(); navigate('/admin/login'); }}
    />
  );
}
