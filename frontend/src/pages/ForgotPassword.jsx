import { useState } from 'react';
import { Link } from 'react-router-dom';
import { api, errorMessage } from '../services/api';
import { Alert, Button, Card, Field, Input, Success } from '../components/ui';

export default function ForgotPassword() {
  const [email, setEmail] = useState('');
  const [token, setToken] = useState('');
  const [message, setMessage] = useState('');
  const [error, setError] = useState('');

  async function submit(event) {
    event.preventDefault();
    setError('');
    try {
      const response = await api.post('/auth/forgot-password', { email });
      setMessage(response.data.message);
      setToken(response.data.reset_token || '');
    } catch (err) {
      setError(errorMessage(err));
    }
  }

  return (
    <Card className="mx-auto max-w-md space-y-4">
      <h1 className="font-display text-4xl">Reset password</h1>
      <Alert>{error}</Alert>
      <Success>{message}</Success>
      <form onSubmit={submit} className="space-y-3">
        <Field label="Email"><Input type="email" value={email} onChange={(event) => setEmail(event.target.value)} required /></Field>
        <Button type="submit" variant="pine">Send reset token</Button>
      </form>
      {token ? (
        <p className="text-sm">
          Use this token on the next screen: <span className="font-medium">{token}</span>
          <br />
          <Link className="text-clay" to={`/reset-password?email=${encodeURIComponent(email)}&token=${encodeURIComponent(token)}`}>Continue</Link>
        </p>
      ) : null}
    </Card>
  );
}
