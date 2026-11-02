# The Case of the Missing Features — investigation notes

Student: Thirunavukkarasu palaniappa | CSC 6370 | 2026-11-01

The original archived `formtest.zip` was not available in this conversation. The source below is an independent implementation of the behavior requested by the instructor; I **do not** claim to have observed the specific original starter's baseline results or original network tab.

**Predictions before making the multi-select handler:**
- No selections: the `features` key is omitted from the POST body; PHP should treat it as `[]`.
- One selection: POST contains one `features[]` entry; PHP should print Chat only.
- Three selections: POST contains `features[]=Chat&features[]=Email&features[]=Shell`; PHP should receive an indexed array of three strings.

**Hypothesis and implemented fix:** Standard HTML multi-select fields with `name="features[]"` become an array in PHP's `$_POST['features']`. A scalar field name cannot safely represent all choices in the expected PHP shape. The handler checks `is_array`, deduplicates, validates values against an allowlist and escapes output. Missing optional features return a clear message; unknown or scalar values return controlled errors.

**Local observations after implementation:** `tests/LOCAL_TEST_RESULTS.json` and `tests/POST_CAPTURE_LOCAL.json` record zero, one, three, malformed and unknown selections. They are localhost tests, NOT screenshots of the original starter or CODD DevTools.

**References:** https://www.php.net/manual/en/language.variables.external.php ; https://developer.mozilla.org/en-US/docs/Web/HTML/Reference/Elements/select

**Actual instructor baseline reminder:** If the original `formtest.zip` is available, compare its unmodified POST and HTML/PHP display before reporting a before-and-after repair. Do not show passwords or raw POST dumps.
