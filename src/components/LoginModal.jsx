import { useEffect, useRef } from "react";

export function LoginModal({ open, onClose }) {
  const ref = useRef(null);

  useEffect(() => {
    if (!open) return;
    ref.current?.focus();
    const onKey = (e) => e.key === "Escape" && onClose();
    window.addEventListener("keydown", onKey);
    return () => window.removeEventListener("keydown", onKey);
  }, [open, onClose]);

  if (!open) return null;

  return (
    <div
      className="fixed inset-0 z-50 grid place-items-center bg-chalkboard/60 p-4 backdrop-blur-sm"
      role="dialog"
      aria-modal="true"
      aria-labelledby="login-title"
      onClick={onClose}
    >
      <div
        className="w-full max-w-md rounded-xl border border-border bg-card p-6 sm:p-8"
        style={{ boxShadow: "var(--shadow-lift)", animation: "row-in .25s ease-out both" }}
        onClick={(e) => e.stopPropagation()}
      >
        <p className="eyebrow text-teal">Karibu tena</p>
        <h2 id="login-title" className="mt-1 text-2xl font-semibold text-foreground">
          Sign in to TSMS
        </h2>
        <p className="mt-2 text-sm text-muted-foreground">
          One login for admins, teachers, bursars, parents and students.
        </p>

        <form
          className="mt-6 space-y-4"
          onSubmit={(e) => {
            e.preventDefault();
            onClose();
          }}
        >
          <div>
            <label htmlFor="identity" className="eyebrow text-muted-foreground">
              Phone or email
            </label>
            <input
              ref={ref}
              id="identity"
              name="identity"
              autoComplete="username"
              placeholder="+255 712 345 678"
              className="mt-2 w-full rounded-md border border-input bg-background px-3 py-2.5 text-sm text-foreground outline-none placeholder:text-muted-foreground/70 focus:border-teal focus:ring-2 focus:ring-ring/30"
            />
          </div>
          <div>
            <label htmlFor="password" className="eyebrow text-muted-foreground">
              Password
            </label>
            <input
              id="password"
              name="password"
              type="password"
              autoComplete="current-password"
              placeholder="••••••••"
              className="mt-2 w-full rounded-md border border-input bg-background px-3 py-2.5 text-sm text-foreground outline-none placeholder:text-muted-foreground/70 focus:border-teal focus:ring-2 focus:ring-ring/30"
            />
          </div>
          <button
            type="submit"
            className="w-full rounded-md bg-chalkboard px-4 py-2.5 text-sm font-semibold text-cream transition-colors hover:bg-chalkboard-soft"
          >
            Ingia / Sign in
          </button>
        </form>

        <div className="mt-5 flex items-center justify-between border-t border-border pt-4">
          <p className="font-mono text-[11px] text-muted-foreground">
            Huna akaunti?{" "}
            <a href="/register" className="font-semibold text-clay hover:underline">
              Jisajili
            </a>
          </p>
          <button onClick={onClose} className="text-sm font-medium text-clay hover:underline">
            Close
          </button>
        </div>
      </div>
    </div>
  );
}
