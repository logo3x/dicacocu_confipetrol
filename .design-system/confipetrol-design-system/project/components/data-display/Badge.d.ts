import React from 'react';

export type BadgeTone = 'neutral' | 'brand' | 'accent' | 'success' | 'warning' | 'danger';

export interface BadgeProps {
  children?: React.ReactNode;
  /** @default "neutral" */
  tone?: BadgeTone;
  /** Filled instead of soft-tint. @default false */
  solid?: boolean;
  style?: React.CSSProperties;
}
export function Badge(props: BadgeProps): JSX.Element;
