import { useEffect, useState } from 'react';
import { api, errorMessage, fieldErrors, payload } from '../../services/api';
import { Alert, Badge, Button, Card, Empty, Field, Input, Spinner, Success } from '../../components/ui';

const blank = { label: 'Home', full_name: '', phone: '', line1: '', line2: '', city: '', state: '', postal_code: '', country: 'Nepal', is_default: false };

export default function Addresses() {
  const [rows, setRows] = useState(null);
  const [form, setForm] = useState(blank);
  const [editing, setEditing] = useState(null);
  const [error, setError] = useState('');
  const [errors, setErrors] = useState({});
  const [message, setMessage] = useState('');

  function load() {
    api.get('/addresses').then((response) => setRows(payload(response) || [])).catch((err) => setError(errorMessage(err)));
  }

  useEffect(load, []);

  function set(key, value) {
    setForm((current) => ({ ...current, [key]: value }));
  }

  async function submit(event) {
    event.preventDefault();
    setError('');
    setMessage('');
    try {
      if (editing) await api.put(`/addresses/${editing}`, form);
      else await api.post('/addresses', form);
      setForm(blank);
      setEditing(null);
      setMessage(editing ? 'Address updated.' : 'Address saved.');
      load();
    } catch (err) {
      setErrors(fieldErrors(err));
      setError(errorMessage(err));
    }
  }

  async function remove(id) {
    await api.delete(`/addresses/${id}`);
    load();
  }

  if (!rows) return error ? <Alert>{error}</Alert> : <Spinner label="Loading addresses" />;

  return (
    <div className="grid gap-6 lg:grid-cols-2">
      <div className="space-y-3">
        <h1 className="font-display text-4xl">Addresses</h1>
        {!rows.length ? <Empty title="No addresses" body="Add the place orders should go." /> : rows.map((row) => (
          <Card key={row.id}>
            <div className="flex items-center gap-2">
              <h2 className="font-medium">{row.label}</h2>
              {row.is_default ? <Badge tone="pine">Default</Badge> : null}
            </div>
            <p className="mt-2 text-sm text-muted">{row.full_name}, {row.line1}, {row.city} {row.postal_code}, {row.country}</p>
            <div className="mt-3 flex gap-2">
              <Button variant="line" onClick={() => { setEditing(row.id); setForm(row); }}>Edit</Button>
              <Button variant="ghost" onClick={() => remove(row.id)}>Remove</Button>
            </div>
          </Card>
        ))}
      </div>
      <Card>
        <h2 className="font-display text-2xl">{editing ? 'Edit address' : 'New address'}</h2>
        <Alert>{error}</Alert>
        <Success>{message}</Success>
        <form onSubmit={submit} className="mt-4 grid gap-3">
          <Field label="Label" error={errors.label}><Input value={form.label || ''} onChange={(event) => set('label', event.target.value)} /></Field>
          <Field label="Full name" error={errors.full_name}><Input value={form.full_name} onChange={(event) => set('full_name', event.target.value)} required /></Field>
          <Field label="Phone" error={errors.phone}><Input value={form.phone} onChange={(event) => set('phone', event.target.value)} required /></Field>
          <Field label="Street" error={errors.line1}><Input value={form.line1} onChange={(event) => set('line1', event.target.value)} required /></Field>
          <Field label="Apartment" error={errors.line2}><Input value={form.line2 || ''} onChange={(event) => set('line2', event.target.value)} /></Field>
          <div className="grid grid-cols-2 gap-3">
            <Field label="City" error={errors.city}><Input value={form.city} onChange={(event) => set('city', event.target.value)} required /></Field>
            <Field label="Postal code" error={errors.postal_code}><Input value={form.postal_code} onChange={(event) => set('postal_code', event.target.value)} required /></Field>
          </div>
          <Field label="Country" error={errors.country}><Input value={form.country} onChange={(event) => set('country', event.target.value)} required /></Field>
          <label className="flex items-center gap-2 text-sm"><input type="checkbox" checked={!!form.is_default} onChange={(event) => set('is_default', event.target.checked)} /> Default address</label>
          <Button type="submit" variant="pine">{editing ? 'Update' : 'Save address'}</Button>
        </form>
      </Card>
    </div>
  );
}
