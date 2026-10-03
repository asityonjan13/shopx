import { useEffect, useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { api, errorMessage, money, payload } from '../services/api';
import { Alert, Button, Empty, Select, Spinner, Success } from '../components/ui';

export default function Cart() {
  const navigate = useNavigate();
  const [cart, setCart] = useState(null);
  const [addresses, setAddresses] = useState([]);
  const [addressId, setAddressId] = useState('');
  const [error, setError] = useState('');
  const [notice, setNotice] = useState('');
  const [busy, setBusy] = useState(false);

  function load() {
    return Promise.all([
      api.get('/cart').then((response) => setCart(payload(response))),
      api.get('/addresses').then((response) => {
        const rows = payload(response) || [];
        setAddresses(rows);
        const preferred = rows.find((row) => row.is_default) || rows[0];
        if (preferred) setAddressId(String(preferred.id));
      }),
    ]).catch((err) => setError(errorMessage(err)));
  }

  useEffect(() => { load(); }, []);

  async function changeQty(item, quantity) {
    setError('');
    try {
      const response = await api.put(`/cart/items/${item.id}`, { quantity });
      setCart(payload(response));
    } catch (err) {
      setError(errorMessage(err));
    }
  }

  async function remove(item) {
    const response = await api.delete(`/cart/items/${item.id}`);
    setCart(payload(response));
  }

  async function checkout() {
    setBusy(true);
    setError('');
    try {
      const response = await api.post('/orders', { address_id: Number(addressId) });
      navigate(`/account/orders/${response.data.data.id}`, { state: { notice: 'Order placed. We will pack it from the seller’s shelf.' } });
    } catch (err) {
      setError(errorMessage(err));
      setNotice('');
    } finally {
      setBusy(false);
    }
  }

  if (!cart) return error ? <Alert>{error}</Alert> : <Spinner label="Loading your cart" />;

  const shipping = cart.subtotal >= 75 || cart.subtotal === 0 ? 0 : 8;

  return (
    <div className="grid gap-6 lg:grid-cols-[1.4fr_0.8fr]">
      <div className="space-y-4">
        <h1 className="font-display text-4xl">Cart</h1>
        <Success>{notice}</Success>
        <Alert>{error}</Alert>
        {!cart.items.length ? (
          <Empty title="Your cart is empty" body="The shop is stocked. Start with something you will actually use." action={<Link to="/shop" className="text-clay">Browse products</Link>} />
        ) : cart.items.map((item) => (
          <div key={item.id} className="flex flex-col gap-3 rounded-3xl border border-line bg-card p-4 sm:flex-row sm:items-center">
            <div className="flex-1">
              <Link to={`/shop/${item.product.slug}`} className="font-display text-2xl">{item.product.name}</Link>
              <p className="text-sm text-muted">{money(item.product.selling_price)} each</p>
            </div>
            <input type="number" min="1" value={item.quantity} onChange={(event) => changeQty(item, Number(event.target.value))} className="w-20 rounded-xl border border-line px-3 py-2" />
            <p className="w-24 text-right font-medium">{money(item.line_total)}</p>
            <button type="button" className="text-sm text-muted" onClick={() => remove(item)}>Remove</button>
          </div>
        ))}
      </div>
      <aside className="h-fit space-y-4 rounded-3xl border border-line bg-card p-5">
        <h2 className="font-display text-2xl">Checkout</h2>
        <p className="flex justify-between text-sm"><span>Subtotal</span><span>{money(cart.subtotal)}</span></p>
        <p className="flex justify-between text-sm"><span>Shipping</span><span>{shipping ? money(shipping) : 'Free'}</span></p>
        <p className="flex justify-between font-medium"><span>Total</span><span>{money(cart.subtotal + shipping)}</span></p>
        {addresses.length ? (
          <Select value={addressId} onChange={(event) => setAddressId(event.target.value)}>
            {addresses.map((address) => (
              <option key={address.id} value={address.id}>{address.label}: {address.line1}, {address.city}</option>
            ))}
          </Select>
        ) : (
          <p className="text-sm text-muted">Add an address before you check out. <Link to="/account/addresses" className="text-clay">Save one</Link></p>
        )}
        <Button variant="pine" className="w-full" disabled={busy || !cart.items.length || !addressId} onClick={checkout}>Place order</Button>
      </aside>
    </div>
  );
}
