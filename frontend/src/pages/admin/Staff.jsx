import { useEffect, useState } from 'react';
import { api, errorMessage, fieldErrors, payload } from '../../services/api';
import { Alert, Button, Field, Input, Modal, Select, Spinner, Success } from '../../components/ui';

const blank = { name: '', email: '', password: '', password_confirmation: '', role_id: '' };

export default function Staff() {
  const [rows, setRows] = useState(null);
  const [roles, setRoles] = useState([]);
  const [form, setForm] = useState(blank);
  const [editing, setEditing] = useState(null);
  const [open, setOpen] = useState(false);
  const [error, setError] = useState('');
  const [errors, setErrors] = useState({});
  const [message, setMessage] = useState('');

  function load() {
    Promise.all([
      api.get('/admin/staff').then((response) => setRows(payload(response) || [])),
      api.get('/admin/roles').then((response) => setRoles(payload(response) || [])),
    ]).catch((err) => setError(errorMessage(err)));
  }

  useEffect(load, []);

  function set(key, value) {
    setForm((current) => ({ ...current, [key]: value }));
  }

  async function submit(event) {
    event.preventDefault();
    setError('');
    try {
      if (editing) await api.put(`/admin/staff/${editing}`, form);
      else await api.post('/admin/staff', form);
      setOpen(false);
      setMessage('Staff saved.');
      load();
    } catch (err) {
      setErrors(fieldErrors(err));
      setError(errorMessage(err));
    }
  }

  async function remove(person) {
    if (!window.confirm(`Delete ${person.name}?`)) return;
    try {
      await api.delete(`/admin/staff/${person.id}`);
      load();
    } catch (err) {
      setError(errorMessage(err));
    }
  }

  if (!rows) return error ? <Alert>{error}</Alert> : <Spinner label="Loading staff" />;

  return (
    <div className="space-y-4">
      <div className="flex items-center justify-between">
        <h1 className="font-display text-4xl">Staff</h1>
        <Button variant="pine" onClick={() => { setEditing(null); setForm(blank); setOpen(true); }}>Add staff</Button>
      </div>
      <Success>{message}</Success>
      <Alert>{error}</Alert>
      {rows.map((person) => (
        <div key={person.id} className="flex flex-wrap items-center justify-between gap-3 rounded-3xl border border-line bg-card px-4 py-4">
          <div>
            <p className="font-medium">{person.name}</p>
            <p className="text-sm text-muted">{person.email} · {(person.roles || []).join(', ') || 'No role'}</p>
          </div>
          <div className="flex gap-2">
            <Button variant="line" onClick={() => { setEditing(person.id); setForm({ ...blank, name: person.name, email: person.email, role_id: roles.find((role) => person.roles?.includes(role.name))?.id || '' }); setOpen(true); }}>Edit</Button>
            <Button variant="ghost" onClick={() => remove(person)}>Delete</Button>
          </div>
        </div>
      ))}
      <Modal open={open} title={editing ? 'Edit staff' : 'New staff'} onClose={() => setOpen(false)}>
        <form onSubmit={submit} className="space-y-3">
          <Field label="Name" error={errors.name}><Input value={form.name} onChange={(event) => set('name', event.target.value)} required /></Field>
          <Field label="Email" error={errors.email}><Input type="email" value={form.email} onChange={(event) => set('email', event.target.value)} required /></Field>
          <Field label="Role" error={errors.role_id}>
            <Select value={form.role_id} onChange={(event) => set('role_id', event.target.value)} required>
              <option value="">Choose a role</option>
              {roles.map((role) => <option key={role.id} value={role.id}>{role.name}</option>)}
            </Select>
          </Field>
          <Field label={editing ? 'New password' : 'Password'} error={errors.password}><Input type="password" value={form.password} onChange={(event) => set('password', event.target.value)} required={!editing} /></Field>
          <Field label="Confirm password"><Input type="password" value={form.password_confirmation} onChange={(event) => set('password_confirmation', event.target.value)} /></Field>
          <Button type="submit" variant="pine">Save</Button>
        </form>
      </Modal>
    </div>
  );
}
