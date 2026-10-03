export function Button({ variant = 'primary', className = '', type = 'button', ...props }) {
  const styles = {
    primary: 'bg-clay text-white hover:bg-[#a84324]',
    pine: 'bg-pine text-white hover:bg-[#163c2d]',
    line: 'border border-line bg-card text-ink hover:border-ink',
    ghost: 'text-ink hover:bg-black/5',
    danger: 'bg-red-800 text-white hover:bg-red-900',
  };

  return (
    <button
      type={type}
      className={`inline-flex items-center justify-center gap-2 rounded-full px-4 py-2.5 text-sm font-medium transition disabled:cursor-not-allowed disabled:opacity-50 ${styles[variant]} ${className}`}
      {...props}
    />
  );
}

export function Field({ label, error, children }) {
  return (
    <label className="block space-y-1.5 text-sm">
      <span className="font-medium text-ink">{label}</span>
      {children}
      {error ? <span className="block text-red-700">{Array.isArray(error) ? error[0] : error}</span> : null}
    </label>
  );
}

const control = 'w-full rounded-xl border border-line bg-card px-3 py-2.5 text-ink outline-none ring-clay/30 focus:ring-4';

export function Input(props) {
  return <input className={control} {...props} />;
}

export function Select(props) {
  return <select className={control} {...props} />;
}

export function TextArea(props) {
  return <textarea className={`${control} min-h-28`} {...props} />;
}

export function Badge({ children, tone = 'clay' }) {
  const styles = {
    clay: 'bg-[#f8e6df] text-clay',
    pine: 'bg-[#e5f2eb] text-pine',
    ink: 'bg-ink text-white',
    muted: 'bg-[#efe8de] text-muted',
  };
  return <span className={`inline-flex rounded-full px-2.5 py-1 text-xs font-medium ${styles[tone]}`}>{children}</span>;
}

export function Card({ className = '', children }) {
  return <section className={`rounded-3xl border border-line bg-card p-5 shadow-[0_1px_0_rgba(28,25,21,0.04)] ${className}`}>{children}</section>;
}

export function Empty({ title, body, action }) {
  return (
    <Card className="text-center">
      <h2 className="font-display text-2xl">{title}</h2>
      <p className="mx-auto mt-2 max-w-md text-muted">{body}</p>
      {action ? <div className="mt-5">{action}</div> : null}
    </Card>
  );
}

export function Spinner({ label = 'Loading' }) {
  return (
    <div className="flex items-center gap-3 py-10 text-muted">
      <span className="h-4 w-4 animate-spin rounded-full border-2 border-line border-t-clay" />
      {label}
    </div>
  );
}

export function Alert({ children }) {
  if (!children) return null;
  return <div className="rounded-2xl border border-[#efd3c8] bg-[#fff4ef] px-4 py-3 text-sm text-[#8a341c]">{children}</div>;
}

export function Success({ children }) {
  if (!children) return null;
  return <div className="rounded-2xl border border-[#cfe3d6] bg-[#f3faf5] px-4 py-3 text-sm text-pine">{children}</div>;
}

export function Modal({ open, title, onClose, children }) {
  if (!open) return null;
  return (
    <div className="fixed inset-0 z-50 flex items-end justify-center bg-ink/40 p-4 sm:items-center">
      <div className="max-h-[90vh] w-full max-w-lg overflow-auto rounded-3xl bg-card p-5 shadow-xl">
        <div className="mb-4 flex items-start justify-between gap-4">
          <h2 className="font-display text-2xl">{title}</h2>
          <button type="button" onClick={onClose} className="text-sm text-muted">Close</button>
        </div>
        {children}
      </div>
    </div>
  );
}

export function DataTable({ columns, rows, empty = 'Nothing here yet.' }) {
  if (!rows?.length) {
    return <p className="py-8 text-center text-muted">{empty}</p>;
  }

  return (
    <div className="overflow-x-auto">
      <table className="w-full min-w-[640px] text-left text-sm">
        <thead className="text-xs uppercase tracking-wide text-muted">
          <tr>
            {columns.map((column) => (
              <th key={column.key} className="border-b border-line px-3 py-3 font-medium">{column.label}</th>
            ))}
          </tr>
        </thead>
        <tbody>
          {rows.map((row) => (
            <tr key={row.id} className="border-b border-line/80">
              {columns.map((column) => (
                <td key={column.key} className="px-3 py-3 align-middle">{column.render ? column.render(row) : row[column.key]}</td>
              ))}
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}
