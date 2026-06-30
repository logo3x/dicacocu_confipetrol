import React from 'react';

export interface SwitchProps {
  label?: React.ReactNode;
  checked?: boolean;
  /** Receives the next boolean value. */
  onChange?: (next: boolean) => void;
  disabled?: boolean;
  id?: string;
  style?: React.CSSProperties;
}
export function Switch(props: SwitchProps): JSX.Element;
