import { useEffect, useState } from 'react';
import { Link, useNavigate, useParams } from 'react-router-dom';
import { api, errorMessage, fieldErrors, payload } from '../../services/api';
import { Alert, Button, Card, Empty, Field, Input, Spinner, Success } from '../../components/ui';

export function RoleList() {
  const [roles, setRoles] = useState(null);
  const [error, setError] = useState('');

  useEffect(() => {
    api.get('/admin/roles').then((response) => setRoles(payload(response) || [])).catch((err) => setError(errorMessage(err)));
  }, []);

  async function remove(role) {
    if (!window.confirm(`Delete ${role.name}?`)) return;
    try {
      await api.delete(`/admin/roles/${role.id}`);
      setRoles((current) => current.filter((item) => item.id !== role.id));
    } catch (err) {
      setError(errorMessage(err));
    }
  }

  if (error && !roles) return <Alert>{error}</Alert>;
  if (!roles) return <Spinner label="Loading roles" />;

  return (
    <div className="space-y-4">
      <div className="flex items-center justify-between">
        <h1 className="font-display text-4xl">Roles</h1>
        <Link to="/admin/roles/new" className="rounded-full bg-pine px-4 py-2 text-sm text-white">New role</Link>
      </div>
      <Alert>{error}</Alert>
      {!roles.length ? <Empty title="No roles" body="Create a role and attach permissions." /> : roles.map((role) => (
        <div key={role.id} className="flex items-center justify-between rounded-3xl border border-line bg-card px-4 py-4">
          <div>
            <p className="font-medium">{role.name}</p>
            <p className="text-sm text-muted">{role.permissions_count ?? role.permissions?.length ?? 0} permissions</p>
          </div>
          <div className="flex gap-2">
            <Link to={`/admin/roles/${role.id}`} className="rounded-full border border-line px-3 py-2 text-sm">Edit</Link>
            <Button variant="ghost" onClick={() => remove(role)}>Delete</Button>
          </div>
        </div>
      ))}
    </div>
  );
}

export function RoleForm() {
  const { id } = useParams();
  const navigate = useNavigate();
  const [groups, setGroups] = useState(null);
  const [name, setName] = useState('');
  const [selected, setSelected] = useState([]);
  const [error, setError] = useState('');
  const [errors, setErrors] = useState({});
  const [message, setMessage] = useState('');

  useEffect(() => {
    api.get('/admin/permissions').then((response) => setGroups(payload(response) || {})).catch((err) => setError(errorMessage(err)));
    if (!id) return;
    api.get(`/admin/roles/${id}`).then((response) => {
      const role = payload(response);
      setName(role.name);
      setSelected(role.permissions || []);
    }).catch((err) => setError(errorMessage(err)));
  }, [id]);

  function toggle(permission) {
    setSelected((current) => current.includes(permission) ? current.filter((item) => item !== permission) : [...current, permission]);
  }

  async function submit(event) {
    event.preventDefault();
    setError('');
    try {
      const body = { name, permissions: selected };
      if (id) await api.put(`/admin/roles/${id}`, body);
      else await api.post('/admin/roles', body);
      setMessage('Role saved.');
      navigate('/admin/roles', { state: { notice: 'Role saved.' } });
    } catch (err) {
      setErrors(fieldErrors(err));
      setError(errorMessage(err));
    }
  }

  if (!groups) return <Spinner label="Loading permissions" />;

  return (
    <Card className="max-w-2xl">
      <h1 className="font-display text-4xl">{id ? 'Edit role' : 'New role'}</h1>
      <Alert>{error}</Alert>
      <Success>{message}</Success>
      <form onSubmit={submit} className="mt-4 space-y-4">
        <Field label="Name" error={errors.name}><Input value={name} onChange={(event) => setName(event.target.value)} required /></Field>
        {Object.entries(groups).map(([group, permissions]) => (
          <fieldset key={group}>
            <legend className="text-sm font-medium">{group}</legend>
            <div className="mt-2 space-y-2">
              {permissions.map((permission) => (
                <label key={permission} className="flex items-center gap-2 text-sm">
                  <input type="checkbox" checked={selected.includes(permission)} onChange={() => toggle(permission)} />
                  {permission}
                </label>
              ))}
            </div>
          </fieldset>
        ))}
        <Button type="submit" variant="pine">Save role</Button>
      </form>
    </Card>
  );
}
