import React from 'react';

export type ButtonVariant = 'primary' | 'accent' | 'secondary' | 'ghost' | 'danger';
export type ButtonSize = 'sm' | 'md' | 'lg';

/**
 * Primary action control. Use `accent` (orange) for the single hero CTA on a view,
 * `primary` (blue) for standard confirms, `secondary`/`ghost` for lower emphasis.
 *
 * @startingPoint section="Actions" subtitle="Brand buttons — blue, orange, ghost" viewport="700x180"
 */
export interface ButtonProps {
  children?: React.ReactNode;
  /** Visual emphasis. @default "primary" */
  variant?: ButtonVariant;
  /** @default "md" */
  size?: ButtonSize;
  /** Stretch to fill container width. @default false */
  block?: boolean;
  disabled?: boolean;
  iconLeft?: React.ReactNode;
  iconRight?: React.ReactNode;
  type?: 'button' | 'submit' | 'reset';
  onClick?: (e: React.MouseEvent<HTMLButtonElement>) => void;
  style?: React.CSSProperties;
}

export function Button(props: ButtonProps): JSX.Element;
