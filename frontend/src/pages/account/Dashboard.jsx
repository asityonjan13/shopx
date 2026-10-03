import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { api, errorMessage, money, payload } from '../../services/api';
import { Alert, Badge, Card, Spinner } from '../../components/ui';

export default function Dashboard() {
  const [data, setData] = useState(null);
  const [error, setError] = useState('');

  useEffect(() => {
    api.get('/dashboard').then((response) => setData(payload(response))).catch((err) => setError(errorMessage(err)));
  }, []);

  if (error) return <Alert>{error}</Alert>;
  if (!data) return <Spinner label="Loading your account" />;

  const stats = [
    ['Orders', data.orders_count],
    ['Open', data.open_orders],
    ['Saved', data.wishlist_count],
    ['Addresses', data.addresses_count],
  ];

  return (
    <div className="space-y-6">
      <h1 className="font-display text-4xl">Your account</h1>
      <div className="grid gap-3 sm:grid-cols-4">
        {stats.map(([label, value]) => (
          <Card key={label}><p className="text-sm text-muted">{label}</p><p className="font-display text-3xl">{value}</p></Card>
        ))}
      </div>
      {data.user_type === 'vendor' ? (
        <Card>
          <p className="text-sm text-muted">Vendor identity</p>
          <p className="mt-1 font-medium">{data.kyc_status ? `KYC is ${data.kyc_status}.` : 'KYC has not been submitted.'}</p>
          <Link to="/account/kyc" className="mt-3 inline-block text-sm text-clay">Review identity check</Link>
        </Card>
      ) : null}
      <div className="space-y-3">
        <h2 className="font-display text-2xl">Recent orders</h2>
        {!data.recent_orders.length ? <p className="text-muted">No orders yet.</p> : data.recent_orders.map((order) => (
          <Link key={order.id} to={`/account/orders/${order.id}`} className="flex items-center justify-between rounded-2xl border border-line bg-card px-4 py-3">
            <span>{order.number}</span>
            <Badge tone="pine">{order.status}</Badge>
            <span>{money(order.total)}</span>
          </Link>
        ))}
      </div>
    </div>
  );
}
