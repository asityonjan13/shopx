import { useEffect, useState } from 'react';
import { api, errorMessage, fieldErrors, payload } from '../../services/api';
import { Alert, Badge, Button, Card, Field, Input, Select, Spinner, Success, TextArea } from '../../components/ui';

export default function Kyc() {
  const [record, setRecord] = useState(undefined);
  const [message, setMessage] = useState('');
  const [error, setError] = useState('');
  const [errors, setErrors] = useState({});

  useEffect(() => {
    api.get('/kyc').then((response) => setRecord(payload(response))).catch((err) => setError(errorMessage(err)));
  }, []);

  async function submit(event) {
    event.preventDefault();
    setError('');
    setMessage('');
    try {
      const response = await api.post('/kyc', new FormData(event.target));
      setRecord(response.data.data);
      setMessage(response.data.message);
    } catch (err) {
      setErrors(fieldErrors(err));
      setError(errorMessage(err));
    }
  }

  if (record === undefined && !error) return <Spinner label="Checking identity status" />;

  const locked = record && ['pending', 'approved'].includes(record.status);

  return (
    <div className="max-w-xl space-y-4">
      <h1 className="font-display text-4xl">Identity check</h1>
      <p className="text-muted">Vendors send a document before products can go on sale.</p>
      {record ? <Badge tone={record.status === 'approved' ? 'pine' : 'clay'}>{record.status}</Badge> : null}
      {record?.rejected_reason ? <Alert>{record.rejected_reason}</Alert> : null}
      <Alert>{error}</Alert>
      <Success>{message}</Success>
      {locked ? (
        <Card><p>Your application is {record.status}. {record.status === 'pending' ? 'An admin will review the document.' : 'You can publish products.'}</p></Card>
      ) : (
        <Card>
          <form onSubmit={submit} className="space-y-3">
            <Field label="Full name" error={errors.full_name}><Input name="full_name" defaultValue={record?.full_name || ''} required /></Field>
            <Field label="Date of birth" error={errors.date_of_birth}><Input name="date_of_birth" type="date" defaultValue={record?.date_of_birth || ''} required /></Field>
            <Field label="Gender" error={errors.gender}>
              <Select name="gender" defaultValue={record?.gender || 'female'}>
                <option value="female">Female</option>
                <option value="male">Male</option>
              </Select>
            </Field>
            <Field label="Full address" error={errors.full_address}><TextArea name="full_address" defaultValue={record?.full_address || ''} required /></Field>
            <Field label="Document" error={errors.document_type}>
              <Select name="document_type" defaultValue={record?.document_type || 'id_card'}>
                <option value="id_card">ID card</option>
                <option value="passport">Passport</option>
                <option value="driving_license">Driving license</option>
              </Select>
            </Field>
            <Field label="Scan" error={errors.document_scan_copy}><Input name="document_scan_copy" type="file" accept="image/*,.pdf" required /></Field>
            <Button type="submit" variant="pine">Submit for review</Button>
          </form>
        </Card>
      )}
    </div>
  );
}
