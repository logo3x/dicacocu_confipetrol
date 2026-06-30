import React from 'react';

export interface InputProps {
  label?: string;
  value?: string;
  onChange?: (e: React.ChangeEvent<HTMLInputElement>) => void;
  placeholder?: string;
  type?: string;
  /** Helper text shown below the field. */
  helper?: string;
  /** Error message — overrides helper and turns the field red. */
  error?: string;
  disabled?: boolean;
  iconLeft?: React.ReactNode;
  id?: string;
  style?: React.CSSProperties;
}

export function Input(props: InputProps): JSX.Element;
