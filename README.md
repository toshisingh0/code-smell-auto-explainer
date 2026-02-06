# Code Smell Auto Explainer

Code Smell Auto Explainer is a backend-focused tool that analyzes PHP/Laravel source code and automatically detects common code smells.  
The goal of this project is to help developers identify poor design practices early and understand *why* a piece of code is considered a code smell.

---

## 🚀 Features Implemented

### ✅ Fat Controller Smell Detection
Detects controllers that violate the Single Responsibility Principle by:
- Excessive number of methods
- Too many lines of code
- High cyclomatic complexity
- Heavy business logic inside controllers

**Metrics analyzed:**
- Total lines of code
- Number of methods
- Complexity score
- Variable usage

---

### ✅ God Method Smell Detection
Identifies methods that try to do too much work and become hard to maintain.

**Detection is based on:**
- Method length
- Number of variables used
- Logical complexity
- Multiple responsibilities inside a single method

---

## 🧠 How It Works
1. Source code is tokenized using PHP’s tokenizer
2. Metrics are calculated for classes and methods
3. Predefined thresholds are applied
4. If thresholds are exceeded, a code smell is detected
5. A human-readable explanation is generated

---

## 🛠 Tech Stack
- PHP
- Laravel
- PHP Tokenizer
- Blade (for result visualization)

---



