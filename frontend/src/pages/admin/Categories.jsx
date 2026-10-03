import { useEffect, useState } from 'react';
import { api, errorMessage, fieldErrors, payload } from '../../services/api';
import { Alert, Button, Field, Input, Modal, Select, Spinner, Success } from '../../components/ui';

function Node({ category, onEdit, onDelete }) {
  return (
    <li className="space-y-2">
      <div className="flex flex-wrap items-center justify-between gap-2 rounded-2xl border border-line bg-card px-3 py-3">
        <div>
          <p className="font-medium">{category.name}</p>
          <p className="text-xs text-muted">{category.slug} · {category.is_active ? 'Visible' : 'Hidden'}</p>
        </div>
        <div className="flex gap-2">
          <Button variant="line" onClick={() => onEdit(category)}>Edit</Button>
          <Button variant="ghost" onClick={() => onDelete(category)}>Delete</Button>
        </div>
      </div>
      {category.children?.length ? (
        <ul className="ml-4 space-y-2 border-l border-line pl-4">
          {category.children.map((child) => <Node key={child.id} category={child} onEdit={onEdit} onDelete={onDelete} />)}
        </ul>
      ) : null}
    </li>
  );
}

function flatten(categories, depth = 0, list = []) {
  categories.forEach((category) => {
    list.push({ ...category, label: `${'— '.repeat(depth)}${category.name}` });
    flatten(category.children || [], depth + 1, list);
  });
  return list;
}

export default function Categories() {
  const [tree, setTree] = useState(null);
  const [open, setOpen] = useState(false);
  const [editing, setEditing] = useState(null);
  const [error, setError] = useState('');
  const [errors, setErrors] = useState({});
  const [message, setMessage] = useState('');

  function load() {
    api.get('/admin/categories').then((response) => setTree(payload(response) || [])).catch((err) => setError(errorMessage(err)));
  }

  useEffect(load, []);

  function startCreate() {
    setEditing(null);
    setOpen(true);
  }

  async function submit(event) {
    event.preventDefault();
    const form = new FormData(event.target);
    const body = {
      name: form.get('name'),
      slug: form.get('slug') || null,
      parent_id: form.get('parent_id') || null,
      is_active: form.get('is_active') === '1',
    };
    setError('');
    try {
      if (editing) await api.put(`/admin/categories/${editing.id}`, body);
      else await api.post('/admin/categories', body);
      setMessage(editing ? 'Category updated.' : 'Category created.');
      setOpen(false);
      load();
    } catch (err) {
      setErrors(fieldErrors(err));
      setError(errorMessage(err));
    }
  }

  async function remove(category) {
    if (!window.confirm(`Delete ${category.name}?`)) return;
    try {
      await api.delete(`/admin/categories/${category.id}`);
      setMessage('Category deleted.');
      load();
    } catch (err) {
      setError(errorMessage(err));
    }
  }

  if (!tree) return error ? <Alert>{error}</Alert> : <Spinner label="Loading categories" />;
  const options = flatten(tree);

  return (
    <div className="space-y-4">
      <div className="flex items-center justify-between">
        <h1 className="font-display text-4xl">Categories</h1>
        <Button variant="pine" onClick={startCreate}>Add category</Button>
      </div>
      <Success>{message}</Success>
      <Alert>{error}</Alert>
      <ul className="space-y-2">
        {tree.map((category) => <Node key={category.id} category={category} onEdit={(item) => { setEditing(item); setOpen(true); }} onDelete={remove} />)}
      </ul>
      <Modal open={open} title={editing ? 'Edit category' : 'New category'} onClose={() => setOpen(false)}>
        <form onSubmit={submit} className="space-y-3" key={editing?.id || 'new'}>
          <Field label="Name" error={errors.name}><Input name="name" defaultValue={editing?.name || ''} required /></Field>
          <Field label="Slug" error={errors.slug}><Input name="slug" defaultValue={editing?.slug || ''} placeholder="Generated from the name if empty" /></Field>
          <Field label="Parent" error={errors.parent_id}>
            <Select name="parent_id" defaultValue={editing?.parent_id || ''}>
              <option value="">Top level</option>
              {options.filter((option) => option.id !== editing?.id).map((option) => <option key={option.id} value={option.id}>{option.label}</option>)}
            </Select>
          </Field>
          <label className="flex items-center gap-2 text-sm"><input type="checkbox" name="is_active" value="1" defaultChecked={editing?.is_active ?? true} /> Visible in the shop</label>
          <Button type="submit" variant="pine">Save</Button>
        </form>
      </Modal>
    </div>
  );
}
