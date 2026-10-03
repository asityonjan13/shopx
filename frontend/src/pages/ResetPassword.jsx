import { useState } from 'react';
import { Link, useSearchParams } from 'react-router-dom';
import { api, errorMessage, fieldErrors } from '../services/api';
import { Alert, Button, Card, Field, Input, Success } from '../components/ui';

export default function ResetPassword() {
  const [params] = useSearchParams();
  const [form, setForm] = useState({
    email: params.get('email') || '',
    token: params.get('token') || '',
    password: '',
    password_confirmation: '',
  });
  const [message, setMessage] = useState('');
  const [error, setError] = useState('');
  const [errors, setErrors] = useState({});

  function set(key, value) {
    setForm((current) => ({ ...current, [key]: value }));
  }

  async function submit(event) {
    event.preventDefault();
    setError('');
    try {
      const response = await api.post('/auth/reset-password', form);
      setMessage(response.data.message);
    } catch (err) {
      setErrors(fieldErrors(err));
      setError(errorMessage(err));
    }
  }

  return (
    <Card className="mx-auto max-w-md space-y-4">
      <h1 className="font-display text-4xl">Choose a new password</h1>
      <Alert>{error}</Alert>
      <Success>{message}</Success>
      <form onSubmit={submit} className="space-y-3">
        <Field label="Email" error={errors.email}><Input type="email" value={form.email} onChange={(event) => set('email', event.target.value)} required /></Field>
        <Field label="Reset token" error={errors.token}><Input value={form.token} onChange={(event) => set('token', event.target.value)} required /></Field>
        <Field label="New password" error={errors.password}><Input type="password" value={form.password} onChange={(event) => set('password', event.target.value)} required /></Field>
        <Field label="Confirm password"><Input type="password" value={form.password_confirmation} onChange={(event) => set('password_confirmation', event.target.value)} required /></Field>
        <Button type="submit" variant="pine">Update password</Button>
      </form>
      <Link to="/login" className="text-sm text-clay">Back to sign in</Link>
    </Card>
  );
}
