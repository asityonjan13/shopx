import { useEffect, useState } from 'react';
import { Link, useParams } from 'react-router-dom';
import { api, errorMessage, payload } from '../../services/api';
import { Alert, Badge, Button, Card, Field, Spinner, Success, TextArea } from '../../components/ui';

export default function KycDetail() {
  const { id } = useParams();
  const [record, setRecord] = useState(null);
  const [reason, setReason] = useState('');
  const [error, setError] = useState('');
  const [message, setMessage] = useState('');

  useEffect(() => {
    api.get(`/admin/kyc/${id}`).then((response) => {
      const data = payload(response);
      setRecord(data);
      setReason(data.rejected_reason || '');
    }).catch((err) => setError(errorMessage(err)));
  }, [id]);

  async function download() {
    const response = await api.get(`/admin/kyc/${id}/document`, { responseType: 'blob' });
    const url = URL.createObjectURL(response.data);
    const link = document.createElement('a');
    link.href = url;
    link.download = `kyc-${id}`;
    link.click();
    URL.revokeObjectURL(url);
  }

  async function review(status) {
    setError('');
    try {
      const response = await api.put(`/admin/kyc/${id}`, { status, rejected_reason: reason });
      setRecord(response.data.data);
      setMessage(response.data.message);
    } catch (err) {
      setError(errorMessage(err));
    }
  }

  if (error && !record) return <Alert>{error}</Alert>;
  if (!record) return <Spinner label="Loading application" />;

  return (
    <div className="max-w-2xl space-y-4">
      <Link to="/admin/kyc" className="text-sm text-clay">All requests</Link>
      <div className="flex items-center gap-3">
        <h1 className="font-display text-4xl">{record.full_name}</h1>
        <Badge tone="pine">{record.status}</Badge>
      </div>
      <Card>
        <p className="text-sm text-muted">{record.user?.email}</p>
        <p className="mt-3">{record.full_address}</p>
        <p className="mt-2 text-sm text-muted">{record.gender} · born {record.date_of_birth} · {record.document_type.replaceAll('_', ' ')}</p>
        {record.has_document ? <Button variant="line" onClick={download}>Download document</Button> : null}
      </Card>
      <Alert>{error}</Alert>
      <Success>{message}</Success>
      <Field label="Rejection reason"><TextArea value={reason} onChange={(event) => setReason(event.target.value)} /></Field>
      <div className="flex gap-2">
        <Button variant="pine" onClick={() => review('approved')}>Approve</Button>
        <Button variant="danger" onClick={() => review('rejected')}>Reject</Button>
        <Button variant="line" onClick={() => review('pending')}>Mark pending</Button>
      </div>
    </div>
  );
}
