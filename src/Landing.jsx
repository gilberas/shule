import { useState } from "react";
import { RegisterCard } from "./components/RegisterCard";
import { LoginModal } from "./components/LoginModal";
import heroCampus from "./assets/hero-campus.jpg";
import classroom from "./assets/classroom.jpg";
import lab from "./assets/lab.jpg";
import assembly from "./assets/assembly.jpg";
import headmistress from "./assets/headmistress.jpg";

const LADDER = [
  {
    n: "01",
    local: "Awali",
    en: "Pre-Primary",
    classes: "Pre-Unit",
    body: "Simple daily registers, guardian records and readiness notes for the youngest learners.",
  },
  {
    n: "02",
    local: "Msingi",
    en: "Primary",
    classes: "Standard 1–7",
    body: "Subject marks with plain letter grades, class ranking and termly report cards in PDF.",
  },
  {
    n: "03",
    local: "Sekondari",
    en: "O-Level",
    classes: "Form 1–4",
    body: "CATs, mid-terms and finals rolled into NECTA-style points and CSEE division bands.",
  },
  {
    n: "04",
    local: "Sekondari",
    en: "A-Level",
    classes: "Form 5–6",
    body: "Subject combinations per student, ACSEE points and division computed automatically.",
  },
];

const TABS = [
  {
    key: "academics",
    label: "Academics",
    title: "Years, terms, streams and combinations",
    body: "Set the 2026 academic year with three terms, build classes and streams, then assign subjects — including A-Level combinations that differ per student rather than a fixed list.",
    points: ["Academic years & 3-term calendar", "Classes, streams and class teachers", "Subject sets per level"],
    image: classroom,
    alt: "Teacher leading a lesson in a Tanzanian classroom",
  },
  {
    key: "attendance",
    label: "Attendance",
    title: "The morning register, digitised",
    body: "Class and subject teachers mark the stream register in seconds. Parents see history and monthly percentages; guardians of absentees get an SMS the same morning.",
    points: ["Daily register per stream", "Monthly attendance %", "Automatic absence SMS"],
    image: assembly,
    alt: "Aerial view of a school morning assembly",
  },
  {
    key: "fees",
    label: "Fees",
    title: "Control numbers, invoices, receipts",
    body: "Fee structures per class and term generate invoices automatically. The bursar records mobile money or bank payments with the control number and balances update instantly.",
    points: ["Auto-generated invoices", "Tigo Pesa · M-Pesa · Airtel · GEPG", "PDF receipts & due-date reminders"],
    image: heroCampus,
    alt: "School campus entrance at golden hour",
  },
  {
    key: "reports",
    label: "Report Cards",
    title: "Divisions computed, not hand-counted",
    body: "Marks become grades, grades become points, points become a division — with class position and teacher remarks printed on a clean termly or annual report card.",
    points: ["NECTA grading scales", "Automatic class ranking", "Termly & annual PDF report cards"],
    image: lab,
    alt: "Secondary school students working in a science laboratory",
  },
];

const STATS = [
  { v: "150+", l: "Learners seeded" },
  { v: "4", l: "Levels: Awali → Form 6" },
  { v: "6", l: "Role-based dashboards" },
  { v: "3", l: "Terms per academic year" },
];

export default function Landing() {
  const [loginOpen, setLoginOpen] = useState(false);
  const [tab, setTab] = useState("academics");
  const active = TABS.find((t) => t.key === tab) ?? TABS[0];

  return (
    <div className="min-h-screen bg-background text-foreground">
      {/* Nav */}
      <header className="sticky top-0 z-40 border-b border-border bg-background/85 backdrop-blur">
        <nav className="mx-auto grid max-w-6xl grid-cols-[minmax(0,1fr)_auto] items-center gap-4 px-5 py-3.5 lg:px-8">
          <a href="#top" className="flex min-w-0 items-center gap-2.5">
            <span className="grid h-9 w-9 shrink-0 place-items-center rounded-md bg-chalkboard font-display text-base font-bold text-cream">
              T
            </span>
            <span className="min-w-0">
              <span className="block truncate font-display text-lg font-semibold leading-none">TSMS</span>
              <span className="block truncate font-mono text-[10px] uppercase tracking-[0.18em] text-muted-foreground">
                Tanzania
              </span>
            </span>
          </a>
          <div className="flex items-center gap-1 sm:gap-2">
            <div className="hidden items-center gap-1 md:flex">
              {[
                ["Levels", "#levels"],
                ["Modules", "#modules"],
                ["Schools", "#schools"],
              ].map(([label, href]) => (
                <a
                  key={href}
                  href={href}
                  className="rounded-md px-3 py-2 text-sm font-medium text-muted-foreground transition-colors hover:bg-secondary hover:text-foreground"
                >
                  {label}
                </a>
              ))}
            </div>
            <a
              href="/register"
              className="shrink-0 rounded-md border border-chalkboard/25 px-4 py-2 text-sm font-semibold text-chalkboard transition-colors hover:bg-secondary"
            >
              Jisajili
            </a>
            <a
              href="/login"
              className="shrink-0 rounded-md bg-chalkboard px-4 py-2 text-sm font-semibold text-cream transition-colors hover:bg-chalkboard-soft"
            >
              Ingia
            </a>
          </div>
        </nav>
      </header>

      <main id="top">
        {/* Hero */}
        <section className="relative overflow-hidden">
          <div className="mx-auto max-w-6xl px-5 pb-16 pt-12 lg:px-8 lg:pb-24 lg:pt-20">
            <div className="grid items-center gap-12 lg:grid-cols-[1.05fr_0.95fr]">
              <div className="min-w-0">
                <p className="eyebrow text-clay">Shule yote mahali pamoja</p>
                <h1 className="mt-4 font-display text-4xl leading-[1.05] sm:text-5xl lg:text-6xl">
                  Every register,
                  <br />
                  every division,
                  <br />
                  <em className="not-italic text-teal">every shilingi.</em>
                </h1>
                <p className="mt-6 max-w-lg text-base leading-relaxed text-muted-foreground sm:text-lg">
                  TSMS runs a Tanzanian school the way it actually works — from Awali to Form 6. Attendance,
                  NECTA grading, fee control numbers and parent SMS in one place.
                </p>
                <div className="mt-8 flex flex-wrap items-center gap-3">
                  <a
                    href="/register"
                    className="rounded-md bg-chalkboard px-6 py-3 text-sm font-semibold text-cream transition-colors hover:bg-chalkboard-soft"
                  >
                    Jisajili sasa
                  </a>
                  <a
                    href="/login"
                    className="rounded-md border border-chalkboard/25 px-6 py-3 text-sm font-semibold text-chalkboard transition-colors hover:bg-secondary"
                  >
                    Ingia
                  </a>
                </div>
                <dl className="mt-10 grid max-w-md grid-cols-3 gap-6 border-t border-border pt-6">
                  {[
                    ["Awali → F6", "One ladder"],
                    ["NECTA", "Grading built in"],
                    ["+255", "SMS to guardians"],
                  ].map(([v, l]) => (
                    <div key={l}>
                      <dt className="font-mono text-sm font-semibold text-chalkboard">{v}</dt>
                      <dd className="mt-1 text-xs text-muted-foreground">{l}</dd>
                    </div>
                  ))}
                </dl>
              </div>

              <div className="relative min-w-0">
                <div className="overflow-hidden rounded-xl border border-border" style={{ boxShadow: "var(--shadow-lift)" }}>
                  <img
                    src={heroCampus}
                    alt="Students walking through a Tanzanian secondary school campus at golden hour"
                    width={1600}
                    height={1200}
                    className="h-72 w-full object-cover sm:h-[26rem]"
                  />
                </div>
                <div className="relative z-10 -mt-12 ml-auto w-[92%] sm:-mt-16 sm:w-[84%]">
                  <RegisterCard />
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* Ladder */}
        <section id="levels" className="border-y border-border bg-secondary/60">
          <div className="mx-auto max-w-6xl px-5 py-16 lg:px-8 lg:py-24">
            <div className="grid gap-4 md:grid-cols-[minmax(0,1fr)_auto] md:items-end">
              <div className="min-w-0">
                <p className="eyebrow text-teal">Ngazi za elimu</p>
                <h2 className="mt-3 max-w-xl text-3xl sm:text-4xl">The whole education ladder, in order</h2>
              </div>
              <p className="max-w-sm text-sm text-muted-foreground">
                One platform covers pre-primary through A-Level, so a learner keeps a single record from Pre-Unit
                to Form 6.
              </p>
            </div>

            <ol className="mt-12 grid gap-px overflow-hidden rounded-xl border border-border bg-border md:grid-cols-2 lg:grid-cols-4">
              {LADDER.map((s) => (
                <li key={s.n + s.en} className="bg-background p-6 transition-colors hover:bg-card">
                  <span className="font-mono text-xs text-gold">{s.n}</span>
                  <h3 className="mt-4 text-2xl">{s.local}</h3>
                  <p className="mt-1 text-sm font-medium text-teal">{s.en}</p>
                  <p className="mt-3 font-mono text-[11px] uppercase tracking-[0.16em] text-muted-foreground">
                    {s.classes}
                  </p>
                  <p className="mt-4 text-sm leading-relaxed text-muted-foreground">{s.body}</p>
                </li>
              ))}
            </ol>
          </div>
        </section>

        {/* File-tab feature switcher */}
        <section id="modules" className="mx-auto max-w-6xl px-5 py-16 lg:px-8 lg:py-24">
          <p className="eyebrow text-clay">Moduli</p>
          <h2 className="mt-3 max-w-2xl text-3xl sm:text-4xl">
            Four files the school opens every single day
          </h2>

          <div className="mt-10 flex flex-wrap gap-1 border-b border-border" role="tablist" aria-label="Modules">
            {TABS.map((t) => (
              <button
                key={t.key}
                role="tab"
                aria-selected={t.key === tab}
                onClick={() => setTab(t.key)}
                className={[
                  "-mb-px rounded-t-md border border-b-0 px-4 py-2.5 text-sm font-medium transition-colors",
                  t.key === tab
                    ? "border-border bg-card text-foreground"
                    : "border-transparent text-muted-foreground hover:text-foreground",
                ].join(" ")}
              >
                {t.label}
              </button>
            ))}
          </div>

          <div
            key={active.key}
            className="grid gap-8 rounded-b-xl rounded-tr-xl border border-t-0 border-border bg-card p-6 sm:p-9 lg:grid-cols-2 lg:items-center"
            style={{ animation: "row-in .3s ease-out both" }}
          >
            <div className="min-w-0">
              <h3 className="text-2xl sm:text-3xl">{active.title}</h3>
              <p className="mt-4 text-sm leading-relaxed text-muted-foreground sm:text-base">{active.body}</p>
              <ul className="mt-6 space-y-3">
                {active.points.map((p) => (
                  <li key={p} className="flex items-start gap-3 text-sm text-foreground">
                    <span className="mt-0.5 grid h-5 w-5 shrink-0 place-items-center rounded-full bg-teal/12 text-[11px] font-bold text-teal">
                      ✓
                    </span>
                    {p}
                  </li>
                ))}
              </ul>
            </div>
            <div className="overflow-hidden rounded-lg border border-border">
              <img
                src={active.image}
                alt={active.alt}
                loading="lazy"
                width={1200}
                height={900}
                className="h-64 w-full object-cover sm:h-80"
              />
            </div>
          </div>
        </section>

        {/* Stats strip */}
        <section className="bg-chalkboard">
          <div className="mx-auto grid max-w-6xl grid-cols-2 gap-8 px-5 py-14 lg:grid-cols-4 lg:px-8">
            {STATS.map((s) => (
              <div key={s.l}>
                <p className="font-display text-4xl text-gold">{s.v}</p>
                <p className="mt-2 text-sm text-cream/70">{s.l}</p>
              </div>
            ))}
          </div>
        </section>

        {/* Testimonial */}
        <section id="schools" className="mx-auto max-w-6xl px-5 py-16 lg:px-8 lg:py-24">
          <div className="grid gap-10 lg:grid-cols-[auto_minmax(0,1fr)] lg:items-center">
            <img
              src={headmistress}
              alt="Headmistress of Neema Secondary & Primary School"
              loading="lazy"
              width={816}
              height={816}
              className="h-56 w-56 shrink-0 rounded-xl border border-border object-cover"
            />
            <figure className="min-w-0">
              <p className="eyebrow text-teal">Kutoka shuleni</p>
              <blockquote className="mt-4 font-display text-2xl leading-snug sm:text-3xl">
                "We used to close the term with three exercise books of marks and a calculator. Now divisions,
                positions and report cards come out the same afternoon — and parents get the SMS before the
                learner reaches home."
              </blockquote>
              <figcaption className="mt-6 text-sm text-muted-foreground">
                <span className="font-semibold text-foreground">Mwl. Grace Mchome</span> · Headmistress, Neema
                Secondary &amp; Primary School, Dar es Salaam
              </figcaption>
            </figure>
          </div>
        </section>

        {/* CTA */}
        <section className="mx-auto max-w-6xl px-5 pb-20 lg:px-8">
          <div className="overflow-hidden rounded-2xl border border-border bg-secondary px-6 py-14 text-center sm:px-12">
            <p className="eyebrow text-clay">Tayari kuanza?</p>
            <h2 className="mx-auto mt-4 max-w-2xl text-3xl sm:text-4xl">
              Bring your whole school onto one register
            </h2>
            <p className="mx-auto mt-4 max-w-xl text-sm text-muted-foreground sm:text-base">
              Set up your academic year, import your learners from Excel and mark the first register today.
            </p>
            <div className="mt-8 flex flex-wrap justify-center gap-3">
              <a
                href="/register"
                className="rounded-md bg-chalkboard px-6 py-3 text-sm font-semibold text-cream transition-colors hover:bg-chalkboard-soft"
              >
                Jisajili shule yako
              </a>
              <a
                href="/login"
                className="rounded-md border border-chalkboard/25 px-6 py-3 text-sm font-semibold text-chalkboard transition-colors hover:bg-background"
              >
                Ingia kwenye TSMS
              </a>
            </div>
          </div>
        </section>
      </main>

      <footer className="border-t border-border bg-chalkboard">
        <div className="mx-auto grid max-w-6xl gap-10 px-5 py-14 lg:grid-cols-[1.2fr_repeat(3,minmax(0,0.6fr))] lg:px-8">
          <div className="min-w-0">
            <p className="font-display text-2xl text-cream">TSMS</p>
            <p className="mt-3 max-w-xs text-sm leading-relaxed text-cream/65">
              Mfumo wa uendeshaji wa shule — built for Tanzanian schools, from Awali to Form 6.
            </p>
          </div>
          {[
            { h: "Platform", items: ["Attendance", "Exams & grading", "Fees & payments", "Timetable"] },
            { h: "Roles", items: ["Admin", "Teacher", "Accountant", "Parent"] },
            { h: "Contact", items: ["Dar es Salaam", "+255 712 345 678", "hello@tsms.co.tz"] },
          ].map((col) => (
            <div key={col.h} className="min-w-0">
              <p className="eyebrow text-gold">{col.h}</p>
              <ul className="mt-4 space-y-2.5">
                {col.items.map((i) => (
                  <li key={i} className="truncate text-sm text-cream/70">
                    {i}
                  </li>
                ))}
              </ul>
            </div>
          ))}
        </div>
        <div className="border-t border-cream/10">
          <p className="mx-auto max-w-6xl px-5 py-5 font-mono text-[11px] text-cream/50 lg:px-8">
            © 2026 TSMS · Tanzania School Management System
          </p>
        </div>
      </footer>

      <LoginModal open={loginOpen} onClose={() => setLoginOpen(false)} />
    </div>
  );
}
