import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { api, errorMessage, payload } from '../../services/api';
import { ProductGrid } from '../../components/ProductCard';
import { Alert, Button, Empty, Spinner } from '../../components/ui';

export default function Wishlist() {
  const [products, setProducts] = useState(null);
  const [error, setError] = useState('');

  function load() {
    api.get('/wishlist').then((response) => setProducts(payload(response) || [])).catch((err) => setError(errorMessage(err)));
  }

  useEffect(load, []);

  async function remove(id) {
    await api.delete(`/wishlist/${id}`);
    load();
  }

  if (error) return <Alert>{error}</Alert>;
  if (!products) return <Spinner label="Loading saved items" />;
  if (!products.length) return <Empty title="Nothing saved" body="Tap Save on a product to keep it here." action={<Link to="/shop" className="text-clay">Browse the shop</Link>} />;

  return (
    <div className="space-y-4">
      <h1 className="font-display text-4xl">Wishlist</h1>
      <ProductGrid products={products} />
      <div className="flex flex-wrap gap-2">
        {products.map((product) => (
          <Button key={product.id} variant="line" onClick={() => remove(product.id)}>Remove {product.name}</Button>
        ))}
      </div>
    </div>
  );
}
