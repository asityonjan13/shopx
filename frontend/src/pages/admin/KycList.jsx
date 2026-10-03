import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { api, errorMessage, payload } from '../../services/api';
import { Alert, Badge, Button, Spinner } from '../../components/ui';

const filters = ['', 'pending', 'approved', 'rejected'];

export default function KycList() {
  const [status, setStatus] = useState('');
  const [rows, setRows] = useState(null);
  const [error, setError] = useState('');

  useEffect(() => {
    setRows(null);
    api.get('/admin/kyc', { params: status ? { status } : {} })
      .then((response) => setRows(payload(response) || []))
      .catch((err) => setError(errorMessage(err)));
  }, [status]);

  return (
    <div className="space-y-4">
      <h1 className="font-display text-4xl">Identity requests</h1>
      <div className="flex gap-2">
        {filters.map((filter) => (
          <Button key={filter || 'all'} variant={status === filter ? 'pine' : 'line'} onClick={() => setStatus(filter)}>{filter || 'All'}</Button>
        ))}
      </div>
      <Alert>{error}</Alert>
      {!rows ? <Spinner label="Loading requests" /> : rows.map((row) => (
        <Link key={row.id} to={`/admin/kyc/${row.id}`} className="flex items-center justify-between rounded-3xl border border-line bg-card px-4 py-4">
          <div>
            <p className="font-medium">{row.full_name}</p>
            <p className="text-sm text-muted">{row.user?.email} · {row.document_type.replaceAll('_', ' ')}</p>
          </div>
          <Badge tone={row.status === 'approved' ? 'pine' : 'clay'}>{row.status}</Badge>
        </Link>
      ))}
      {rows && !rows.length ? <p className="text-muted">No requests in this list.</p> : null}
    </div>
  );
}
