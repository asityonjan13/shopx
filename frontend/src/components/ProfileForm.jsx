import { useState } from 'react';
import { api, errorMessage, fieldErrors } from '../services/api';
import { useAuth } from '../hooks/useAuth';
import { Alert, Button, Card, Field, Input, Success } from './ui';

export default function ProfileForm({ admin = false }) {
  const { user, admin: adminAccount, setUser, setAdmin } = useAuth();
  const account = admin ? adminAccount : user;
  const prefix = admin ? '/admin' : '';
  const [form, setForm] = useState({
    name: account?.name || '',
    email: account?.email || '',
    current_password: '',
    password: '',
    password_confirmation: '',
  });
  const [message, setMessage] = useState('');
  const [error, setError] = useState('');
  const [errors, setErrors] = useState({});

  function set(key, value) {
    setForm((current) => ({ ...current, [key]: value }));
  }

  async function saveProfile(event) {
    event.preventDefault();
    setError('');
    setMessage('');
    try {
      const body = new FormData(event.target);
      const response = await api.post(`${prefix}/profile`, body);
      if (admin) setAdmin(response.data.data);
      else setUser(response.data.data);
      setMessage('Profile saved.');
    } catch (err) {
      setErrors(fieldErrors(err));
      setError(errorMessage(err));
    }
  }

  async function savePassword(event) {
    event.preventDefault();
    setError('');
    setMessage('');
    try {
      const response = await api.put(`${prefix}/profile/password`, {
        current_password: form.current_password,
        password: form.password,
        password_confirmation: form.password_confirmation,
      });
      setMessage(response.data.message);
      set('current_password', '');
      set('password', '');
      set('password_confirmation', '');
    } catch (err) {
      setErrors(fieldErrors(err));
      setError(errorMessage(err));
    }
  }

  return (
    <div className="grid gap-4 lg:grid-cols-2">
      <Card>
        <h1 className="font-display text-3xl">Profile</h1>
        <Alert>{error}</Alert>
        <Success>{message}</Success>
        <form onSubmit={saveProfile} className="mt-4 space-y-3">
          <Field label="Name" error={errors.name}><Input name="name" value={form.name} onChange={(event) => set('name', event.target.value)} /></Field>
          <Field label="Email" error={errors.email}><Input name="email" type="email" value={form.email} onChange={(event) => set('email', event.target.value)} /></Field>
          <Field label="Avatar"><Input name="avatar" type="file" accept="image/*" /></Field>
          <Button type="submit" variant="pine">Save profile</Button>
        </form>
      </Card>
      <Card>
        <h2 className="font-display text-3xl">Password</h2>
        <form onSubmit={savePassword} className="mt-4 space-y-3">
          <Field label="Current password" error={errors.current_password}><Input type="password" value={form.current_password} onChange={(event) => set('current_password', event.target.value)} /></Field>
          <Field label="New password" error={errors.password}><Input type="password" value={form.password} onChange={(event) => set('password', event.target.value)} /></Field>
          <Field label="Confirm password"><Input type="password" value={form.password_confirmation} onChange={(event) => set('password_confirmation', event.target.value)} /></Field>
          <Button type="submit" variant="line">Update password</Button>
        </form>
      </Card>
    </div>
  );
}
