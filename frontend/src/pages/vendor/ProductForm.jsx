import { useEffect, useState } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import { api, errorMessage, fieldErrors, payload } from '../../services/api';
import { Alert, Button, Card, Field, Input, Select, Spinner, TextArea } from '../../components/ui';

function flatten(categories, depth = 0, list = []) {
  categories.forEach((category) => {
    list.push({ id: category.id, name: `${'— '.repeat(depth)}${category.name}` });
    flatten(category.children || [], depth + 1, list);
  });
  return list;
}

export default function ProductForm() {
  const { id } = useParams();
  const navigate = useNavigate();
  const [categories, setCategories] = useState([]);
  const [product, setProduct] = useState(null);
  const [error, setError] = useState('');
  const [errors, setErrors] = useState({});
  const [ready, setReady] = useState(!id);

  useEffect(() => {
    api.get('/categories').then((response) => setCategories(flatten(payload(response) || []))).catch(() => {});
    if (!id) return;
    api.get(`/vendor/products/${id}`)
      .then((response) => setProduct(payload(response)))
      .catch((err) => setError(errorMessage(err)))
      .finally(() => setReady(true));
  }, [id]);

  async function submit(event) {
    event.preventDefault();
    setError('');
    const body = new FormData(event.target);
    try {
      if (id) await api.post(`/vendor/products/${id}`, body);
      else await api.post('/vendor/products', body);
      navigate('/vendor/products', { state: { notice: id ? 'Product updated.' : 'Product published.' } });
    } catch (err) {
      setErrors(fieldErrors(err));
      setError(errorMessage(err));
    }
  }

  if (!ready) return <Spinner label="Loading product" />;

  return (
    <Card className="max-w-2xl">
      <h1 className="font-display text-4xl">{id ? 'Edit product' : 'New product'}</h1>
      <Alert>{error}</Alert>
      <form onSubmit={submit} className="mt-4 space-y-3">
        <Field label="Name" error={errors.name}><Input name="name" defaultValue={product?.name || ''} required /></Field>
        <Field label="Category" error={errors.category_id}>
          <Select name="category_id" defaultValue={product?.category?.id || ''}>
            <option value="">Uncategorized</option>
            {categories.map((category) => <option key={category.id} value={category.id}>{category.name}</option>)}
          </Select>
        </Field>
        <div className="grid grid-cols-2 gap-3">
          <Field label="Price" error={errors.price}><Input name="price" type="number" step="0.01" min="0" defaultValue={product?.price ?? ''} required /></Field>
          <Field label="Compare at" error={errors.compare_price}><Input name="compare_price" type="number" step="0.01" min="0" defaultValue={product?.compare_price ?? ''} /></Field>
          <Field label="Stock" error={errors.stock}><Input name="stock" type="number" min="0" defaultValue={product?.stock ?? 0} required /></Field>
          <Field label="SKU" error={errors.sku}><Input name="sku" defaultValue={product?.sku || ''} /></Field>
        </div>
        <Field label="Short description" error={errors.short_description}><Input name="short_description" defaultValue={product?.short_description || ''} /></Field>
        <Field label="Description" error={errors.description}><TextArea name="description" defaultValue={product?.description || ''} /></Field>
        <Field label="Flash price" error={errors.flash_price}><Input name="flash_price" type="number" step="0.01" min="0" defaultValue={product?.flash_price ?? ''} /></Field>
        <Field label="Photo" error={errors.image}><Input name="image" type="file" accept="image/*" /></Field>
        <label className="flex items-center gap-2 text-sm"><input type="hidden" name="is_active" value="0" /><input type="checkbox" name="is_active" value="1" defaultChecked={product?.is_active ?? true} /> Visible in the shop</label>
        <label className="flex items-center gap-2 text-sm"><input type="hidden" name="is_featured" value="0" /><input type="checkbox" name="is_featured" value="1" defaultChecked={!!product?.is_featured} /> Featured</label>
        <label className="flex items-center gap-2 text-sm"><input type="hidden" name="is_flash_sale" value="0" /><input type="checkbox" name="is_flash_sale" value="1" defaultChecked={!!product?.is_flash_sale} /> Flash sale</label>
        <Button type="submit" variant="pine">Save product</Button>
      </form>
    </Card>
  );
}
