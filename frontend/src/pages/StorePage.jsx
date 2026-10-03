import { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import { api, errorMessage, payload } from '../services/api';
import { ProductGrid } from '../components/ProductCard';
import { Alert, Spinner } from '../components/ui';

export default function StorePage() {
  const { slug } = useParams();
  const [page, setPage] = useState(null);
  const [error, setError] = useState('');

  useEffect(() => {
    api.get(`/stores/${slug}`)
      .then((response) => setPage(payload(response)))
      .catch((err) => setError(errorMessage(err)));
  }, [slug]);

  if (error) return <Alert>{error}</Alert>;
  if (!page) return <Spinner label="Opening the store" />;

  return (
    <div className="space-y-6">
      <div className="rounded-[2rem] bg-ink px-6 py-8 text-white">
        <p className="text-sm uppercase tracking-[0.2em] text-white/60">Store</p>
        <h1 className="mt-2 font-display text-5xl">{page.store.name}</h1>
        <p className="mt-3 max-w-2xl text-white/75">{page.store.long_description || page.store.short_description}</p>
        <p className="mt-4 text-sm text-white/60">{page.store.address}</p>
      </div>
      <ProductGrid products={page.products || []} />
    </div>
  );
}
