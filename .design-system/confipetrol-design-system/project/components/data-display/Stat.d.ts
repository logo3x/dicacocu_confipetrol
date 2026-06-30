import React from 'react';

export interface StatProps {
  /** The big number, e.g. "82.5 t" or "+15". */
  value: React.ReactNode;
  /** Bold colored label under the number. */
  label: string;
  /** Optional muted sub-line. */
  sublabel?: string;
  icon?: React.ReactNode;
  /** Accent color for label + icon. @default "accent" */
  accent?: 'accent' | 'brand';
  align?: 'left' | 'center';
  style?: React.CSSProperties;
}
export function Stat(props: StatProps): JSX.Element;
