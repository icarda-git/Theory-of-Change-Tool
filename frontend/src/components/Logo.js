import React from 'react';
import { NavLink } from 'react-router-dom';

const Logo = () => (
  <div className="p-text-center p-py-4">
    <NavLink to="/">
      <h1>TOC</h1>
    </NavLink>
  </div>
);

export default Logo;
