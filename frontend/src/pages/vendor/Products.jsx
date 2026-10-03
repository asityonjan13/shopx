import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { api, errorMessage, money, payload } from '../../services/api';
import { Alert, Badge, Button, Empty, Spinner } from '../../components/ui';

export default function VendorProducts() {
  const [products, setProducts] = useState(null);
  const [error, setError] = useState('');

  function load() {
    api.get('/vendor/products').then((response) => setProducts(payload(response) || [])).catch((err) => setError(errorMessage(err)));
  }

  useEffect(load, []);

  async function remove(product) {
    if (!window.confirm(`Remove ${product.name}?`)) return;
    await api.delete(`/vendor/products/${product.id}`);
    load();
  }

  if (error) return <Alert>{error}</Alert>;
  if (!products) return <Spinner label="Loading products" />;

  return (
    <div className="space-y-4">
      <div className="flex items-center justify-between">
        <h1 className="font-display text-4xl">Products</h1>
        <Link to="/vendor/products/new" className="rounded-full bg-pine px-4 py-2 text-sm text-white">Add product</Link>
      </div>
      {!products.length ? <Empty title="No products" body="Create a store profile, finish KYC, then add the first item." /> : products.map((product) => (
        <div key={product.id} className="flex flex-wrap items-center justify-between gap-3 rounded-3xl border border-line bg-card px-4 py-4">
          <div>
            <p className="font-medium">{product.name}</p>
            <p className="text-sm text-muted">{money(product.selling_price)} · {product.stock} in stock</p>
          </div>
          <Badge tone={product.is_active ? 'pine' : 'muted'}>{product.is_active ? 'Active' : 'Hidden'}</Badge>
          <div className="flex gap-2">
            <Link to={`/vendor/products/${product.id}`} className="rounded-full border border-line px-3 py-2 text-sm">Edit</Link>
            <Button variant="ghost" onClick={() => remove(product)}>Delete</Button>
          </div>
        </div>
      ))}
    </div>
  );
}
