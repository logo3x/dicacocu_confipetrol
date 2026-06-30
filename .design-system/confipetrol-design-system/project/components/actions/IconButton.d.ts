import React from 'react';

export type IconButtonVariant = 'primary' | 'accent' | 'secondary' | 'ghost';
export type IconButtonSize = 'sm' | 'md' | 'lg';

/**
 * Square icon-only button. Always pass `label` for accessibility.
 */
export interface IconButtonProps {
  children?: React.ReactNode;
  variant?: IconButtonVariant;
  size?: IconButtonSize;
  /** Accessible label (aria-label). */
  label: string;
  disabled?: boolean;
  onClick?: (e: React.MouseEvent<HTMLButtonElement>) => void;
  style?: React.CSSProperties;
}

export function IconButton(props: IconButtonProps): JSX.Element;
