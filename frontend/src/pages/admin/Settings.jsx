import { useEffect, useState } from 'react';
import { api, errorMessage, fieldErrors, payload } from '../../services/api';
import { Alert, Button, Card, Field, Input, Spinner, Success } from '../../components/ui';

export default function Settings() {
  const [form, setForm] = useState(null);
  const [error, setError] = useState('');
  const [errors, setErrors] = useState({});
  const [message, setMessage] = useState('');

  useEffect(() => {
    api.get('/admin/settings').then((response) => setForm(payload(response))).catch((err) => setError(errorMessage(err)));
  }, []);

  function set(key, value) {
    setForm((current) => ({ ...current, [key]: value }));
  }

  async function submit(event) {
    event.preventDefault();
    setError('');
    try {
      const response = await api.put('/admin/settings', form);
      setMessage(response.data.message);
    } catch (err) {
      setErrors(fieldErrors(err));
      setError(errorMessage(err));
    }
  }

  if (!form) return error ? <Alert>{error}</Alert> : <Spinner label="Loading settings" />;

  return (
    <Card className="max-w-xl">
      <h1 className="font-display text-4xl">General settings</h1>
      <Alert>{error}</Alert>
      <Success>{message}</Success>
      <form onSubmit={submit} className="mt-4 space-y-3">
        <Field label="Site name" error={errors.site_name}><Input value={form.site_name || ''} onChange={(event) => set('site_name', event.target.value)} required /></Field>
        <Field label="Email" error={errors.site_email}><Input type="email" value={form.site_email || ''} onChange={(event) => set('site_email', event.target.value)} /></Field>
        <Field label="Phone" error={errors.site_phone}><Input value={form.site_phone || ''} onChange={(event) => set('site_phone', event.target.value)} /></Field>
        <Button type="submit" variant="pine">Save settings</Button>
      </form>
    </Card>
  );
}
