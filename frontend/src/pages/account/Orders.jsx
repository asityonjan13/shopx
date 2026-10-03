import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { api, errorMessage, money, payload } from '../../services/api';
import { Alert, Badge, Empty, Spinner } from '../../components/ui';

export default function Orders() {
  const [orders, setOrders] = useState(null);
  const [error, setError] = useState('');

  useEffect(() => {
    api.get('/orders').then((response) => setOrders(payload(response) || [])).catch((err) => setError(errorMessage(err)));
  }, []);

  if (error) return <Alert>{error}</Alert>;
  if (!orders) return <Spinner label="Loading orders" />;
  if (!orders.length) return <Empty title="No orders yet" body="When you check out, the receipt will land here." action={<Link to="/shop" className="text-clay">Start shopping</Link>} />;

  return (
    <div className="space-y-3">
      <h1 className="font-display text-4xl">Orders</h1>
      {orders.map((order) => (
        <Link key={order.id} to={`/account/orders/${order.id}`} className="flex flex-wrap items-center justify-between gap-3 rounded-3xl border border-line bg-card px-4 py-4">
          <div>
            <p className="font-medium">{order.number}</p>
            <p className="text-sm text-muted">{new Date(order.created_at).toLocaleDateString()}</p>
          </div>
          <Badge tone="pine">{order.status}</Badge>
          <span>{money(order.total)}</span>
        </Link>
      ))}
    </div>
  );
}
