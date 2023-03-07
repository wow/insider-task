import React from 'react';
import { InertiaLink } from '@inertiajs/inertia-react';

const Nav = () => {
  return (
    <nav>
      <ul>
        <li>
          <InertiaLink href="/">Home</InertiaLink>
        </li>
        <li>
          <InertiaLink href="/teams">Teams</InertiaLink>
        </li>
        <li>
          <InertiaLink href="/leagues">Leagues</InertiaLink>
        </li>
        <li>
          <InertiaLink href="/fixtures">Fixtures</InertiaLink>
        </li>
      </ul>
    </nav>
  );
};

export default Nav;
