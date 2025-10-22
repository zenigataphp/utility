# Contributing

Thank you for your interest in contributing to the **Zenigata** framework! We welcome contributions of all kinds, including bug reports, feature requests, documentation improvements, and code contributions.

## Pull Requests

- Please open an issue first if you plan to submit a major change or new feature. This helps us discuss the best approach and avoid duplicated work.
- Fork the repository and create a feature branch for your changes.
- Make sure your branch is up to date with the `main` branch before submitting a pull request.
- Provide a clear and descriptive title and detailed description of your changes in the pull request.
- Link related issues if applicable.

## Commit Messages

We follow the [Conventional Commits](https://www.conventionalcommits.org/en/v1.0.0/) specification to keep commit history clean and meaningful:

- Use a format like: `<type>: <subject>` — do not use scope
- Breaking changes **must** be indicated by adding an exclamation mark after the type, e.g., `feat!: ...`
- Common types include:
  - `feat`: a new feature
  - `fix`: a bug fix
  - `docs`: documentation only changes
  - `style`: formatting, missing semi colons, etc; no code change
  - `refactor`: code change that neither fixes a bug nor adds a feature
  - `test`: adding or updating tests
  - `chore`: changes to the build process or auxiliary tools

Examples:
  - `feat: add support for custom configuration`
  - `fix: correct typo in validation logic`
  - `docs: improve README introduction`
  - `refactor!: remove deprecated method`

## Coding Style

- Follow [PSR-12](https://www.php-fig.org/psr/psr-12/) coding standards.
- Favor composition over inheritance to enhance flexibility and maintainability.
- Use `declare(strict_types=1);` in all PHP files.
- Type all variables, parameters, and return types explicitly.
- Prefer modern PHP features such as promoted properties, named arguments, `match` expressions, enums, and readonly properties.
- Use short but expressive names for variables and methods to improve readability.
- Include PHPDoc comments on all classes, properties, and methods.
- Write clean, readable, and maintainable code.

## Testing

- Write tests for all new features and bug fixes.
- Run existing tests and make sure all pass before submitting your pull request.
- We use PHPUnit for testing.
- Tests should be isolated and not depend on external systems unless explicitly required.

## Additional Guidelines

- Keep your pull request focused on a single concern to facilitate review.
- Avoid breaking backward compatibility unless it’s necessary and clearly communicated.
- Update documentation as needed when your changes affect usage or behavior.
- Be respectful and constructive in code reviews and discussions.

## Contact

If you have questions or need help, feel free to open an issue.

Thank you for helping improve the **Zenigata** framework!
