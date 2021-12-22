module.exports = {
  env: {
    browser: true,
    es2021: true,
  },
  extends: ['plugin:react/recommended', 'airbnb', 'react-app'],
  parserOptions: {
    ecmaFeatures: {
      jsx: true,
    },
    ecmaVersion: 12,
    sourceType: 'module',
  },
  plugins: ['react'],
  rules: {
    'no-confusing-arrow': ['error', { allowParens: true }],
    'react/jsx-filename-extension': [1, { extensions: ['.js', '.jsx'] }],
    quotes: ['error', 'single', { allowTemplateLiterals: true }],
    'jsx-a11y/label-has-associated-control': [
      2,
      {
        assert: 'either',
        depth: 3,
      },
    ],
    'operator-linebreak': ['error', 'after', { overrides: { '?': 'before', ':': 'before' } }],
    'comma-dangle': [
      'error',
      'always-multiline',
      {
        imports: 'never',
        exports: 'never',
        arrays: 'never',
        objects: 'never',
        functions: 'never',
      },
    ],
    'object-curly-newline': 0,
    'react/jsx-curly-newline': 0,
    'react/prop-types': 0,
    'jsx-a11y/click-events-have-key-events': 0,
    'implicit-arrow-linebreak': 0,
    'react/jsx-one-expression-per-line': 0,
    'function-paren-newline': 0,
  },
};
