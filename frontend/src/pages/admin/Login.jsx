import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../../hooks/useAuth';
import { errorMessage } from '../../services/api';
import { Alert, Button, Card, Field, Input } from '../../components/ui';

export default function AdminLogin() {
  const { loginAdmin } = useAuth();
  const navigate = useNavigate();
  const [form, setForm] = useState({ email: '', password: '' });
  const [error, setError] = useState('');
  const [busy, setBusy] = useState(false);

  async function submit(event) {
    event.preventDefault();
    setBusy(true);
    setError('');
    try {
      await loginAdmin(form);
      navigate('/admin');
    } catch (err) {
      setError(errorMessage(err));
    } finally {
      setBusy(false);
    }
  }

  return (
    <div className="mx-auto flex min-h-screen max-w-md items-center px-4">
      <Card className="w-full space-y-4">
        <p className="text-xs uppercase tracking-[0.2em] text-muted">ShopX admin</p>
        <h1 className="font-display text-4xl">Staff sign in</h1>
        <p className="text-sm text-muted">superadmin@gmail.com / 12345678</p>
        <Alert>{error}</Alert>
        <form onSubmit={submit} className="space-y-3">
          <Field label="Email"><Input type="email" value={form.email} onChange={(event) => setForm({ ...form, email: event.target.value })} required /></Field>
          <Field label="Password"><Input type="password" value={form.password} onChange={(event) => setForm({ ...form, password: event.target.value })} required /></Field>
          <Button type="submit" variant="pine" className="w-full" disabled={busy}>Sign in</Button>
        </form>
      </Card>
    </div>
  );
}
