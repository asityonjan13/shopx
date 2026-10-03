import { useEffect, useState } from 'react';
import { Link, useParams } from 'react-router-dom';
import { api, errorMessage, money, payload } from '../../services/api';
import { Alert, Badge, Card, Spinner } from '../../components/ui';

export default function OrderDetail() {
  const { id } = useParams();
  const [order, setOrder] = useState(null);
  const [error, setError] = useState('');

  useEffect(() => {
    api.get(`/orders/${id}`).then((response) => setOrder(payload(response))).catch((err) => setError(errorMessage(err)));
  }, [id]);

  if (error) return <Alert>{error}</Alert>;
  if (!order) return <Spinner label="Loading order" />;
  const address = order.shipping_address || {};

  return (
    <div className="space-y-4">
      <Link to="/account/orders" className="text-sm text-clay">All orders</Link>
      <div className="flex items-center gap-3">
        <h1 className="font-display text-4xl">{order.number}</h1>
        <Badge tone="pine">{order.status}</Badge>
      </div>
      <div className="grid gap-4 md:grid-cols-2">
        <Card>
          <h2 className="font-medium">Items</h2>
          <ul className="mt-3 space-y-2 text-sm">
            {order.items.map((item) => (
              <li key={item.id} className="flex justify-between"><span>{item.name} × {item.quantity}</span><span>{money(item.line_total)}</span></li>
            ))}
          </ul>
          <p className="mt-4 flex justify-between text-sm"><span>Shipping</span><span>{order.shipping ? money(order.shipping) : 'Free'}</span></p>
          <p className="mt-1 flex justify-between font-medium"><span>Total</span><span>{money(order.total)}</span></p>
        </Card>
        <Card>
          <h2 className="font-medium">Ships to</h2>
          <p className="mt-3 text-sm text-muted">{address.full_name}<br />{address.line1}{address.line2 ? `, ${address.line2}` : ''}<br />{address.city} {address.postal_code}<br />{address.country}<br />{address.phone}</p>
        </Card>
      </div>
    </div>
  );
}
