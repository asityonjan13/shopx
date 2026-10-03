import { useEffect, useState } from 'react';
import { api, errorMessage, money, payload } from '../../services/api';
import { Alert, Card, Spinner } from '../../components/ui';

export default function AdminDashboard() {
  const [data, setData] = useState(null);
  const [error, setError] = useState('');

  useEffect(() => {
    api.get('/admin/dashboard').then((response) => setData(payload(response))).catch((err) => setError(errorMessage(err)));
  }, []);

  if (error) return <Alert>{error}</Alert>;
  if (!data) return <Spinner label="Loading the desk" />;

  const stats = [
    ['Customers', data.customers],
    ['Vendors', data.vendors],
    ['Products', data.products],
    ['Orders', data.orders],
    ['Pending KYC', data.pending_kyc],
    ['Revenue', money(data.revenue)],
  ];

  return (
    <div className="space-y-4">
      <h1 className="font-display text-4xl">Marketplace</h1>
      <div className="grid gap-3 sm:grid-cols-3">
        {stats.map(([label, value]) => (
          <Card key={label}><p className="text-sm text-muted">{label}</p><p className="font-display text-3xl">{value}</p></Card>
        ))}
      </div>
    </div>
  );
}
