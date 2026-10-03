import { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../hooks/useAuth';
import { errorMessage, fieldErrors } from '../services/api';
import { Alert, Button, Card, Field, Input, Select } from '../components/ui';

export default function Register() {
  const { register } = useAuth();
  const navigate = useNavigate();
  const [form, setForm] = useState({ name: '', email: '', password: '', password_confirmation: '', user_type: 'user' });
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
      const account = await register(form);
      navigate(account.user_type === 'vendor' ? '/account/kyc' : '/account');
    } catch (err) {
      setErrors(fieldErrors(err));
      setError(errorMessage(err));
    } finally {
      setBusy(false);
    }
  }

  return (
    <Card className="mx-auto max-w-md space-y-4">
      <h1 className="font-display text-4xl">Create an account</h1>
      <Alert>{error}</Alert>
      <form onSubmit={submit} className="space-y-3">
        <Field label="Name" error={errors.name}><Input value={form.name} onChange={(event) => set('name', event.target.value)} required /></Field>
        <Field label="Email" error={errors.email}><Input type="email" value={form.email} onChange={(event) => set('email', event.target.value)} required /></Field>
        <Field label="I am" error={errors.user_type}>
          <Select value={form.user_type} onChange={(event) => set('user_type', event.target.value)}>
            <option value="user">Shopping for myself</option>
            <option value="vendor">Opening a store</option>
          </Select>
        </Field>
        <Field label="Password" error={errors.password}><Input type="password" value={form.password} onChange={(event) => set('password', event.target.value)} required /></Field>
        <Field label="Confirm password"><Input type="password" value={form.password_confirmation} onChange={(event) => set('password_confirmation', event.target.value)} required /></Field>
        <Button type="submit" variant="pine" className="w-full" disabled={busy}>Create account</Button>
      </form>
      <p className="text-sm text-muted">Already registered? <Link to="/login" className="text-clay">Sign in</Link></p>
    </Card>
  );
}
