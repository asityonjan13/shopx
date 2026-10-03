import { useState } from 'react';
import { Link, useLocation, useNavigate } from 'react-router-dom';
import { useAuth } from '../hooks/useAuth';
import { errorMessage, fieldErrors } from '../services/api';
import { Alert, Button, Card, Field, Input } from '../components/ui';

export default function Login() {
  const { login } = useAuth();
  const navigate = useNavigate();
  const location = useLocation();
  const [form, setForm] = useState({ email: '', password: '' });
  const [error, setError] = useState('');
  const [errors, setErrors] = useState({});
  const [busy, setBusy] = useState(false);

  function set(key, value) {
    setForm((current) => ({ ...current, [key]: value }));
  }

  async function submit(event) {
    event.preventDefault();
    setBusy(true);
    setError('');
    try {
      const account = await login(form);
      const fallback = account.user_type === 'vendor' ? '/vendor' : '/account';
      navigate(location.state?.from || fallback);
    } catch (err) {
      setErrors(fieldErrors(err));
      setError(errorMessage(err));
    } finally {
      setBusy(false);
    }
  }

  return (
    <Card className="mx-auto max-w-md space-y-4">
      <h1 className="font-display text-4xl">Sign in</h1>
      <p className="text-sm text-muted">Customer demo: user@gmail.com / 12345678. Vendor: vendor@gmail.com / 12345678.</p>
      <Alert>{error}</Alert>
      <form onSubmit={submit} className="space-y-3">
        <Field label="Email" error={errors.email}><Input type="email" value={form.email} onChange={(event) => set('email', event.target.value)} required /></Field>
        <Field label="Password" error={errors.password}><Input type="password" value={form.password} onChange={(event) => set('password', event.target.value)} required /></Field>
        <Button type="submit" variant="pine" className="w-full" disabled={busy}>Sign in</Button>
      </form>
      <p className="text-sm text-muted">New here? <Link to="/register" className="text-clay">Create an account</Link>. <Link to="/forgot-password" className="text-clay">Forgot password</Link></p>
    </Card>
  );
}
