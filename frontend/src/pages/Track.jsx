import { useState } from 'react';
import { api, errorMessage, money, payload } from '../services/api';
import { Alert, Badge, Button, Card, Input } from '../components/ui';

export default function Track() {
  const [number, setNumber] = useState('');
  const [order, setOrder] = useState(null);
  const [error, setError] = useState('');

  async function submit(event) {
    event.preventDefault();
    setError('');
    setOrder(null);
    try {
      const response = await api.get(`/orders/track/${encodeURIComponent(number.trim())}`);
      setOrder(payload(response));
    } catch (err) {
      setError(errorMessage(err));
    }
  }

  return (
    <div className="mx-auto max-w-xl space-y-4">
      <h1 className="font-display text-4xl">Track an order</h1>
      <p className="text-muted">Use the number from your confirmation, like SX-00012.</p>
      <form onSubmit={submit} className="flex gap-2">
        <Input value={number} onChange={(event) => setNumber(event.target.value)} placeholder="SX-00012" />
        <Button type="submit" variant="pine">Look up</Button>
      </form>
      <Alert>{error}</Alert>
      {order ? (
        <Card>
          <div className="flex items-center justify-between">
            <h2 className="font-display text-2xl">{order.number}</h2>
            <Badge tone="pine">{order.status}</Badge>
          </div>
          <ul className="mt-4 space-y-2 text-sm">
            {order.items.map((item) => (
              <li key={item.id} className="flex justify-between"><span>{item.name} × {item.quantity}</span><span>{money(item.line_total)}</span></li>
            ))}
          </ul>
          <p className="mt-4 text-right font-medium">{money(order.total)}</p>
        </Card>
      ) : null}
    </div>
  );
}
