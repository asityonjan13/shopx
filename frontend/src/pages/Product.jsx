import { useEffect, useState } from 'react';
import { Link, useNavigate, useParams } from 'react-router-dom';
import { api, errorMessage, money, payload, tone } from '../services/api';
import { useAuth } from '../hooks/useAuth';
import { Alert, Badge, Button, Spinner, Success } from '../components/ui';

export default function Product() {
  const { slug } = useParams();
  const { user } = useAuth();
  const navigate = useNavigate();
  const [product, setProduct] = useState(null);
  const [error, setError] = useState('');
  const [notice, setNotice] = useState('');
  const [quantity, setQuantity] = useState(1);
  const [busy, setBusy] = useState(false);

  useEffect(() => {
    setProduct(null);
    api.get(`/products/${slug}`)
      .then((response) => setProduct(payload(response)))
      .catch((err) => setError(errorMessage(err)));
  }, [slug]);

  async function addToCart() {
    if (!user) {
      navigate('/login', { state: { from: `/shop/${slug}` } });
      return;
    }
    setBusy(true);
    setError('');
    try {
      await api.post('/cart', { product_id: product.id, quantity });
      setNotice('Added to your cart.');
    } catch (err) {
      setError(errorMessage(err));
    } finally {
      setBusy(false);
    }
  }

  async function save() {
    if (!user) {
      navigate('/login', { state: { from: `/shop/${slug}` } });
      return;
    }
    setBusy(true);
    setError('');
    try {
      await api.post(`/wishlist/${product.id}`);
      setNotice('Saved to your wishlist.');
    } catch (err) {
      setError(errorMessage(err));
    } finally {
      setBusy(false);
    }
  }

  if (error && !product) return <Alert>{error}</Alert>;
  if (!product) return <Spinner label="Loading product" />;

  return (
    <div className="grid gap-8 md:grid-cols-2">
      <div className="flex min-h-80 items-end rounded-[2rem] p-6 text-white" style={{ background: `linear-gradient(160deg, ${tone(product.name)}, #1c1915)` }}>
        {product.image_url ? <img src={product.image_url} alt="" className="h-full w-full rounded-2xl object-cover" /> : <span className="font-display text-7xl">{product.name.slice(0, 1)}</span>}
      </div>
      <div className="space-y-4">
        <Link to={product.store?.slug ? `/stores/${product.store.slug}` : '/shop'} className="text-sm uppercase tracking-wide text-muted">{product.store?.name}</Link>
        <h1 className="font-display text-5xl leading-none">{product.name}</h1>
        <div className="flex gap-2">
          {product.is_flash_sale ? <Badge>Flash price</Badge> : null}
          <Badge tone={product.stock > 0 ? 'pine' : 'muted'}>{product.stock > 0 ? `${product.stock} in stock` : 'Out of stock'}</Badge>
        </div>
        <p className="text-2xl">{money(product.selling_price)}{product.compare_price ? <span className="ml-2 text-base text-muted line-through">{money(product.compare_price)}</span> : null}</p>
        <p className="text-muted">{product.description || product.short_description}</p>
        <Success>{notice}</Success>
        <Alert>{error}</Alert>
        <div className="flex flex-wrap items-center gap-3">
          <input type="number" min="1" max={product.stock || 1} value={quantity} onChange={(event) => setQuantity(Number(event.target.value))} className="w-20 rounded-xl border border-line bg-card px-3 py-2" />
          <Button variant="pine" disabled={busy || product.stock < 1} onClick={addToCart}>Add to cart</Button>
          <Button variant="line" disabled={busy} onClick={save}>Save</Button>
        </div>
      </div>
    </div>
  );
}
