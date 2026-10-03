import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { api, errorMessage, payload } from '../../services/api';
import { Alert, Card, Spinner } from '../../components/ui';

export default function VendorDashboard() {
  const [data, setData] = useState(null);
  const [error, setError] = useState('');

  useEffect(() => {
    api.get('/vendor/dashboard').then((response) => setData(payload(response))).catch((err) => setError(errorMessage(err)));
  }, []);

  if (error) return <Alert>{error}</Alert>;
  if (!data) return <Spinner label="Loading the vendor desk" />;

  return (
    <div className="space-y-6">
      <h1 className="font-display text-4xl">{data.store?.name || 'Your store'}</h1>
      <div className="grid gap-3 sm:grid-cols-3">
        <Card><p className="text-sm text-muted">Products</p><p className="font-display text-3xl">{data.products_count}</p></Card>
        <Card><p className="text-sm text-muted">On sale</p><p className="font-display text-3xl">{data.active_products}</p></Card>
        <Card><p className="text-sm text-muted">Low stock</p><p className="font-display text-3xl">{data.low_stock}</p></Card>
      </div>
      <Card>
        <p className="text-sm text-muted">Identity</p>
        <p className="mt-1">{data.kyc_status ? `KYC is ${data.kyc_status}.` : 'Submit KYC before publishing products.'}</p>
        <div className="mt-4 flex gap-4 text-sm">
          <Link to="/vendor/store" className="text-clay">Edit store profile</Link>
          <Link to="/vendor/products" className="text-clay">Manage products</Link>
          <Link to="/account/kyc" className="text-clay">Identity check</Link>
        </div>
      </Card>
    </div>
  );
}
