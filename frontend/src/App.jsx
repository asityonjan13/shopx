import { Navigate, Outlet, Route, Routes, useLocation } from 'react-router-dom';
import { useAuth } from './hooks/useAuth';
import { AccountLayout, AdminLayout, StorefrontLayout, VendorLayout } from './components/layouts';
import { Spinner } from './components/ui';
import Home from './pages/Home';
import Shop from './pages/Shop';
import Product from './pages/Product';
import StorePage from './pages/StorePage';
import Cart from './pages/Cart';
import Track from './pages/Track';
import Login from './pages/Login';
import Register from './pages/Register';
import ForgotPassword from './pages/ForgotPassword';
import ResetPassword from './pages/ResetPassword';
import Dashboard from './pages/account/Dashboard';
import Orders from './pages/account/Orders';
import OrderDetail from './pages/account/OrderDetail';
import Wishlist from './pages/account/Wishlist';
import Addresses from './pages/account/Addresses';
import Profile from './pages/account/Profile';
import Kyc from './pages/account/Kyc';
import VendorDashboard from './pages/vendor/Dashboard';
import VendorStore from './pages/vendor/Store';
import VendorProducts from './pages/vendor/Products';
import ProductForm from './pages/vendor/ProductForm';
import AdminLogin from './pages/admin/Login';
import AdminDashboard from './pages/admin/Dashboard';
import Categories from './pages/admin/Categories';
import KycList from './pages/admin/KycList';
import KycDetail from './pages/admin/KycDetail';
import { RoleForm, RoleList } from './pages/admin/Roles';
import Staff from './pages/admin/Staff';
import Settings from './pages/admin/Settings';
import AdminProfile from './pages/admin/Profile';

function Gate({ ready, allowed, to, children }) {
  const location = useLocation();
  if (!ready) return <Spinner label="Checking your session" />;
  if (!allowed) return <Navigate to={to} state={{ from: location.pathname }} replace />;
  return children || <Outlet />;
}

function UserGate() {
  const { user, ready } = useAuth();
  return <Gate ready={ready} allowed={!!user} to="/login" />;
}

function VendorGate() {
  const { user, ready } = useAuth();
  return <Gate ready={ready} allowed={user?.user_type === 'vendor'} to={user ? '/account' : '/login'} />;
}

function AdminGate() {
  const { admin, ready } = useAuth();
  return <Gate ready={ready} allowed={!!admin} to="/admin/login" />;
}

export default function App() {
  return (
    <Routes>
      <Route element={<StorefrontLayout />}>
        <Route index element={<Home />} />
        <Route path="shop" element={<Shop />} />
        <Route path="shop/:slug" element={<Product />} />
        <Route path="stores/:slug" element={<StorePage />} />
        <Route path="track" element={<Track />} />
        <Route path="login" element={<Login />} />
        <Route path="register" element={<Register />} />
        <Route path="forgot-password" element={<ForgotPassword />} />
        <Route path="reset-password" element={<ResetPassword />} />
        <Route element={<UserGate />}>
          <Route path="cart" element={<Cart />} />
        </Route>
      </Route>

      <Route element={<UserGate />}>
        <Route element={<AccountLayout />}>
          <Route path="account" element={<Dashboard />} />
          <Route path="account/orders" element={<Orders />} />
          <Route path="account/orders/:id" element={<OrderDetail />} />
          <Route path="account/wishlist" element={<Wishlist />} />
          <Route path="account/addresses" element={<Addresses />} />
          <Route path="account/profile" element={<Profile />} />
          <Route path="account/kyc" element={<Kyc />} />
        </Route>
        <Route element={<VendorGate />}>
          <Route element={<VendorLayout />}>
            <Route path="vendor" element={<VendorDashboard />} />
            <Route path="vendor/store" element={<VendorStore />} />
            <Route path="vendor/products" element={<VendorProducts />} />
            <Route path="vendor/products/new" element={<ProductForm />} />
            <Route path="vendor/products/:id" element={<ProductForm />} />
          </Route>
        </Route>
      </Route>

      <Route path="admin/login" element={<AdminLogin />} />
      <Route element={<AdminGate />}>
        <Route element={<AdminLayout />}>
          <Route path="admin" element={<AdminDashboard />} />
          <Route path="admin/categories" element={<Categories />} />
          <Route path="admin/kyc" element={<KycList />} />
          <Route path="admin/kyc/:id" element={<KycDetail />} />
          <Route path="admin/roles" element={<RoleList />} />
          <Route path="admin/roles/new" element={<RoleForm />} />
          <Route path="admin/roles/:id" element={<RoleForm />} />
          <Route path="admin/staff" element={<Staff />} />
          <Route path="admin/settings" element={<Settings />} />
          <Route path="admin/profile" element={<AdminProfile />} />
        </Route>
      </Route>

      <Route path="*" element={<Navigate to="/" replace />} />
    </Routes>
  );
}
