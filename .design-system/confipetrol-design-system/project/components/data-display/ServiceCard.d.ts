import React from 'react';

/**
 * Service tile — icon, title, blurb, CTA. Composes `Card`.
 * @startingPoint section="Layout" subtitle="Service tile grid (Mantenimiento, Overhaul…)" viewport="700x300"
 */
export interface ServiceCardProps {
  icon?: React.ReactNode;
  title: string;
  description: string;
  href?: string;
  /** CTA text. @default "Conócelo" */
  cta?: string;
  style?: React.CSSProperties;
}
export function ServiceCard(props: ServiceCardProps): JSX.Element;
