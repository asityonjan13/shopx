import axios from 'axios';

export const api = axios.create({
  baseURL: '/api',
  headers: { Accept: 'application/json' },
});

api.interceptors.request.use((config) => {
  const url = config.url || '';
  const key = url.startsWith('/admin') ? 'shopx_admin_token' : 'shopx_token';
  const token = localStorage.getItem(key);
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export function payload(response) {
  return response.data?.data;
}

export function errorMessage(error) {
  const data = error?.response?.data;
  if (data?.errors) {
    const first = Object.values(data.errors)[0];
    return Array.isArray(first) ? first[0] : String(first);
  }
  return data?.message || 'Something went wrong. Try again.';
}

export function fieldErrors(error) {
  return error?.response?.data?.errors || {};
}

export function money(value) {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
  }).format(Number(value || 0));
}

export function tone(name = '') {
  const colors = ['#1e4d3a', '#c4512c', '#31456b', '#8a5a2b', '#24515b', '#6b3a4a'];
  const index = [...name].reduce((sum, char) => sum + char.charCodeAt(0), 0) % colors.length;
  return colors[index];
}
