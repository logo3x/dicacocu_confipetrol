import React from 'react';

export type AlertTone = 'info' | 'success' | 'warning' | 'danger';

export interface AlertProps {
  /** @default "info" */
  tone?: AlertTone;
  title?: string;
  children?: React.ReactNode;
  /** Override the default tone icon. */
  icon?: React.ReactNode;
  /** Show a close button; receives the click. */
  onClose?: () => void;
  style?: React.CSSProperties;
}
export function Alert(props: AlertProps): JSX.Element;
