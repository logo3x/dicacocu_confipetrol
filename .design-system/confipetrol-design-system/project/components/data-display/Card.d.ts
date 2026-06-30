import React from 'react';

/**
 * Surface container — the base for most boxed content.
 * @startingPoint section="Layout" subtitle="Cards, KPI stats, service tiles" viewport="700x300"
 */
export interface CardProps {
  children?: React.ReactNode;
  /** Resting drop shadow. @default false */
  elevated?: boolean;
  /** Orange brand bar across the top edge. @default false */
  accentTop?: boolean;
  /** Internal padding. @default true */
  padded?: boolean;
  /** Lift + deepen shadow on hover. @default false */
  hoverable?: boolean;
  style?: React.CSSProperties;
}
export function Card(props: CardProps): JSX.Element;
