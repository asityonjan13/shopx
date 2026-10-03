import { useEffect, useState } from 'react';
import { useSearchParams } from 'react-router-dom';
import { api, errorMessage, payload } from '../services/api';
import { ProductGrid } from '../components/ProductCard';
import { Alert, Button, Empty, Input, Select, Spinner } from '../components/ui';

export default function Shop() {
  const [params, setParams] = useSearchParams();
  const [products, setProducts] = useState([]);
  const [meta, setMeta] = useState(null);
  const [categories, setCategories] = useState([]);
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(true);
  const [query, setQuery] = useState(params.get('q') || '');

  useEffect(() => {
    api.get('/categories').then((response) => setCategories(payload(response) || [])).catch(() => {});
  }, []);

  useEffect(() => {
    setLoading(true);
    setError('');
    api.get('/products', { params: Object.fromEntries(params.entries()) })
      .then((response) => {
        setProducts(payload(response) || []);
        setMeta(response.data.meta || null);
      })
      .catch((err) => setError(errorMessage(err)))
      .finally(() => setLoading(false));
  }, [params]);

  function update(next) {
    const merged = Object.fromEntries(params.entries());
    Object.entries(next).forEach(([key, value]) => {
      if (!value) delete merged[key];
      else merged[key] = value;
    });
    if (!('page' in next)) delete merged.page;
    setParams(merged);
  }

  return (
    <div className="space-y-6">
      <div>
        <h1 className="font-display text-4xl">Shop</h1>
        <p className="mt-1 text-muted">Search the catalog, or narrow it by department.</p>
      </div>
      <form
        className="grid gap-3 md:grid-cols-[1fr_180px_180px_auto]"
        onSubmit={(event) => {
          event.preventDefault();
          update({ q: query });
        }}
      >
        <Input value={query} onChange={(event) => setQuery(event.target.value)} placeholder="Search headphones, lamps, trousers" />
        <Select value={params.get('category') || ''} onChange={(event) => update({ category: event.target.value })}>
          <option value="">All departments</option>
          {categories.map((category) => (
            <option key={category.id} value={category.slug}>{category.name}</option>
          ))}
        </Select>
        <Select value={params.get('sort') || ''} onChange={(event) => update({ sort: event.target.value })}>
          <option value="">Newest</option>
          <option value="price_asc">Price, low to high</option>
          <option value="price_desc">Price, high to low</option>
          <option value="name">Name</option>
        </Select>
        <Button type="submit" variant="pine">Search</Button>
      </form>
      {error ? <Alert>{error}</Alert> : null}
      {loading ? <Spinner label="Finding products" /> : null}
      {!loading && !products.length ? <Empty title="No matches" body="Try another word, or clear the department filter." /> : null}
      {!loading && products.length ? <ProductGrid products={products} /> : null}
      {meta && meta.last_page > 1 ? (
        <div className="flex items-center justify-between text-sm">
          <span className="text-muted">Page {meta.current_page} of {meta.last_page}</span>
          <div className="flex gap-2">
            <Button variant="line" disabled={meta.current_page <= 1} onClick={() => update({ page: String(meta.current_page - 1) })}>Previous</Button>
            <Button variant="line" disabled={meta.current_page >= meta.last_page} onClick={() => update({ page: String(meta.current_page + 1) })}>Next</Button>
          </div>
        </div>
      ) : null}
    </div>
  );
}
