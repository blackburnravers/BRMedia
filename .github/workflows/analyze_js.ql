// CodeQL script to analyze JavaScript files

import javascript

// Function to read JavaScript files
class ReadJSFiles extends Expr {
  ReadJSFiles() { this.getFile().getFileType() = "JavaScript" }
}

// Example: Find all function declarations in JavaScript files
from FunctionDeclaration fd
select fd, fd.getName()