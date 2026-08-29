import { useEffect, useState } from "react";

const ROSTER = [
  { no: "TSMS/2026/0114", name: "Neema J. Mwakalinga", stream: "Form 3B" },
  { no: "TSMS/2026/0115", name: "Baraka S. Mushi", stream: "Form 3B" },
  { no: "TSMS/2026/0116", name: "Amina H. Kileo", stream: "Form 3B" },
  { no: "TSMS/2026/0117", name: "Joseph M. Nyerere", stream: "Form 3B" },
  { no: "TSMS/2026/0118", name: "Zawadi P. Lyimo", stream: "Form 3B" },
  { no: "TSMS/2026/0119", name: "Emmanuel K. Sanga", stream: "Form 3B" },
];

export function RegisterCard() {
  const [marks, setMarks] = useState(() => ROSTER.map(() => null));

  useEffect(() => {
    if (typeof window !== "undefined" && window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
      setMarks(ROSTER.map((_, i) => (i === 3 ? "absent" : "present")));
      return;
    }
    let step = 0;
    const id = window.setInterval(() => {
      if (step < ROSTER.length) {
        const i = step;
        setMarks((prev) => {
          const next = [...prev];
          next[i] = i === 3 ? "absent" : "present";
          return next;
        });
      } else if (step >= ROSTER.length + 4) {
        setMarks(ROSTER.map(() => null));
        step = -1;
      }
      step += 1;
    }, 620);
    return () => window.clearInterval(id);
  }, []);


  const present = marks.filter((m) => m === "present").length;

  return (
    <div
      className="w-full rounded-lg border border-border bg-card p-5 sm:p-6"
      style={{ boxShadow: "var(--shadow-paper)" }}
      aria-label="Daily attendance register preview"
    >
      <div className="grid grid-cols-[minmax(0,1fr)_auto] items-start gap-4 border-b border-border pb-4">
        <div className="min-w-0">
          <p className="eyebrow text-teal">Daftari la Mahudhurio</p>
          <h3 className="mt-1 truncate text-lg font-semibold text-foreground">Form 3B · Daily Register</h3>
        </div>
        <span className="shrink-0 rounded-full bg-chalkboard px-3 py-1 font-mono text-[11px] text-cream">
          {present}/{ROSTER.length}
        </span>
      </div>

      <ul className="paper-lines mt-1 divide-y divide-border/70">
        {ROSTER.map((s, i) => (
          <li
            key={s.no}
            className="grid grid-cols-[minmax(0,1fr)_auto] items-center gap-3 py-3"
            style={{ animation: `row-in .5s ease-out ${i * 0.06}s both` }}
          >
            <div className="min-w-0">
              <p className="truncate text-sm font-medium text-foreground">{s.name}</p>
              <p className="truncate font-mono text-[11px] text-muted-foreground">{s.no}</p>
            </div>
            <span
              className={[
                "grid h-7 w-7 shrink-0 place-items-center rounded-full border text-[13px] font-bold",
                marks[i] === "present"
                  ? "border-teal/30 bg-teal/12 text-teal"
                  : marks[i] === "absent"
                    ? "border-clay/30 bg-clay/12 text-clay"
                    : "border-dashed border-border text-muted-foreground/40",
              ].join(" ")}
              style={marks[i] ? { animation: "tick-in .35s ease-out both" } : undefined}
            >
              {marks[i] === "present" ? "✓" : marks[i] === "absent" ? "✕" : "·"}
            </span>
          </li>
        ))}
      </ul>

      <div className="mt-4 flex items-center justify-between rounded-md bg-secondary px-3 py-2">
        <p className="font-mono text-[11px] text-muted-foreground">SMS kwa mzazi · 1 absentee</p>
        <p className="font-mono text-[11px] font-semibold text-chalkboard">+255 7•• ••• 418</p>
      </div>
    </div>
  );
}
