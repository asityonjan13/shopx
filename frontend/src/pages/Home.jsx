import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { api, errorMessage, payload } from '../services/api';
import { ProductGrid } from '../components/ProductCard';
import { Alert, Spinner } from '../components/ui';

export default function Home() {
  const [home, setHome] = useState(null);
  const [error, setError] = useState('');

  useEffect(() => {
    api.get('/home')
      .then((response) => setHome(payload(response)))
      .catch((err) => setError(errorMessage(err)));
  }, []);

  if (error) return <Alert>{error}</Alert>;
  if (!home) return <Spinner label="Opening the shop" />;

  return (
    <div className="space-y-12">
      <section className="grid items-center gap-8 rounded-[2rem] bg-pine px-6 py-10 text-white md:grid-cols-[1.3fr_0.7fr] md:px-10">
        <div>
          <p className="text-sm uppercase tracking-[0.2em] text-white/70">{home.settings.site_name || 'ShopX'}</p>
          <h1 className="mt-3 font-display text-5xl leading-[0.95] md:text-7xl">Goods with a maker’s name on them.</h1>
          <p className="mt-4 max-w-xl text-white/80">Audio, home, and clothing from Northwind Goods and the vendors who join next. Free shipping on orders over $75.</p>
          <Link to="/shop" className="mt-6 inline-flex rounded-full bg-white px-5 py-3 text-sm font-medium text-pine">Browse the shop</Link>
        </div>
        <div className="rounded-[1.5rem] bg-[#163c2d] p-5">
          <p className="text-sm text-white/70">This week</p>
          <p className="mt-2 font-display text-3xl">{home.flash_sale[0]?.name || 'New arrivals are in'}</p>
          <p className="mt-2 text-sm text-white/70">{home.flash_sale.length} flash prices, {home.featured.length} featured pieces.</p>
        </div>
      </section>

      <section>
        <div className="mb-4 flex items-end justify-between">
          <h2 className="font-display text-3xl">Departments</h2>
        </div>
        <div className="flex flex-wrap gap-2">
          {home.categories.map((category) => (
            <Link key={category.id} to={`/shop?category=${category.slug}`} className="rounded-full border border-line bg-card px-4 py-2 text-sm hover:border-ink">
              {category.name}
            </Link>
          ))}
        </div>
      </section>

      <section className="space-y-4">
        <div className="flex items-end justify-between">
          <h2 className="font-display text-3xl">Featured</h2>
          <Link to="/shop?featured=1" className="text-sm text-clay">See all</Link>
        </div>
        <ProductGrid products={home.featured} />
      </section>

      <section className="space-y-4">
        <div className="flex items-end justify-between">
          <h2 className="font-display text-3xl">Flash sale</h2>
          <Link to="/shop?flash=1" className="text-sm text-clay">Shop the sale</Link>
        </div>
        {home.flash_sale.length ? <ProductGrid products={home.flash_sale} /> : <p className="text-muted">No flash prices right now.</p>}
      </section>

      <section className="space-y-4">
        <h2 className="font-display text-3xl">Just in</h2>
        <ProductGrid products={home.new_arrivals} />
      </section>
    </div>
  );
}
