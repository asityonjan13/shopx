import { useEffect, useState } from 'react';
import { api, errorMessage, fieldErrors, payload } from '../../services/api';
import { Alert, Button, Card, Field, Input, Spinner, Success, TextArea } from '../../components/ui';

export default function VendorStore() {
  const [store, setStore] = useState(undefined);
  const [error, setError] = useState('');
  const [errors, setErrors] = useState({});
  const [message, setMessage] = useState('');

  useEffect(() => {
    api.get('/vendor/store').then((response) => setStore(payload(response))).catch((err) => setError(errorMessage(err)));
  }, []);

  async function submit(event) {
    event.preventDefault();
    setError('');
    setMessage('');
    try {
      const response = await api.post('/vendor/store', new FormData(event.target));
      setStore(response.data.data);
      setMessage(response.data.message);
    } catch (err) {
      setErrors(fieldErrors(err));
      setError(errorMessage(err));
    }
  }

  if (store === undefined && !error) return <Spinner label="Loading store profile" />;

  return (
    <Card className="max-w-2xl">
      <h1 className="font-display text-4xl">Store profile</h1>
      <Alert>{error}</Alert>
      <Success>{message}</Success>
      <form onSubmit={submit} className="mt-4 space-y-3">
        <Field label="Name" error={errors.name}><Input name="name" defaultValue={store?.name || ''} required /></Field>
        <Field label="Email" error={errors.email}><Input name="email" type="email" defaultValue={store?.email || ''} required /></Field>
        <Field label="Phone" error={errors.phone}><Input name="phone" defaultValue={store?.phone || ''} required /></Field>
        <Field label="Address" error={errors.address}><Input name="address" defaultValue={store?.address || ''} required /></Field>
        <Field label="Short description" error={errors.short_description}><Input name="short_description" defaultValue={store?.short_description || ''} required /></Field>
        <Field label="About the store" error={errors.long_description}><TextArea name="long_description" defaultValue={store?.long_description || ''} required /></Field>
        <Field label="Logo" error={errors.logo}><Input name="logo" type="file" accept="image/*" /></Field>
        <Field label="Banner" error={errors.banner}><Input name="banner" type="file" accept="image/*" /></Field>
        <Button type="submit" variant="pine">Save store</Button>
      </form>
    </Card>
  );
}
