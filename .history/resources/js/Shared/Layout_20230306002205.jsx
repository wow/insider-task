import React from 'react';
import PropTypes from 'prop-types';
import Nav from './Nav';

const Layout = ({ title, children }) => {
  return (
    <div>
      <Nav />

      <h1>{title}</h1>

      {children}
    </div>
  );
};

Layout.propTypes = {
  title: PropTypes.string.isRequired,
  children: PropTypes.node.isRequired,
};

export default Layout;
