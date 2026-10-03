import { Link } from 'react-router-dom';
import { money, tone } from '../services/api';
import { Badge } from './ui';

export function ProductCard({ product }) {
  const color = tone(product.name);

  return (
    <Link to={`/shop/${product.slug}`} className="group flex flex-col overflow-hidden rounded-3xl border border-line bg-card">
      <div className="relative flex aspect-[4/5] items-end p-4 text-white" style={{ background: `linear-gradient(160deg, ${color}, #1c1915 140%)` }}>
        {product.image_url ? (
          <img src={product.image_url} alt="" className="absolute inset-0 h-full w-full object-cover" />
        ) : (
          <span className="font-display text-4xl leading-none">{product.name.slice(0, 1)}</span>
        )}
        <div className="absolute left-3 top-3 flex gap-2">
          {product.is_flash_sale ? <Badge>Flash</Badge> : null}
          {product.is_featured ? <Badge tone="pine">Featured</Badge> : null}
        </div>
      </div>
      <div className="flex flex-1 flex-col gap-2 p-4">
        <p className="text-xs uppercase tracking-wide text-muted">{product.store?.name || product.category?.name}</p>
        <h3 className="font-display text-xl leading-tight group-hover:text-clay">{product.name}</h3>
        <p className="line-clamp-2 text-sm text-muted">{product.short_description}</p>
        <p className="mt-auto pt-3 text-sm">
          <span className="font-semibold">{money(product.selling_price)}</span>
          {product.compare_price && product.compare_price > product.selling_price ? (
            <span className="ml-2 text-muted line-through">{money(product.compare_price)}</span>
          ) : null}
        </p>
      </div>
    </Link>
  );
}

export function ProductGrid({ products }) {
  return (
    <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
      {products.map((product) => <ProductCard key={product.id} product={product} />)}
    </div>
  );
}
