// CodeQL script to analyze PHP files

import php

// Function to read PHP files
class ReadPHPFiles extends Expr {
  ReadPHPFiles() { this.getFile().getFileType() = "PHP" }
}

// Example: Find all function declarations in PHP files
from FunctionDeclaration fd
select fd, fd.getName()