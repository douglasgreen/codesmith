<?php

declare(strict_types=1);

namespace DouglasGreen\Syntax\Exceptions;

// Base class for program exceptions
abstract class BaseException extends \Exception {}

/**
 * Data-related exceptions for invalid or unrecognized data
 */
class DataException extends BaseException {}

/**
 * Thrown when an ID is duplicated when it should be unique
 */
class DuplicateValueException extends DataException {}

/**
 * Thrown for JSON-related errors
 *
 * @see https://www.php.net/manual/en/ref.json.php
 */
class JsonException extends DataException {}

/**
 * Thrown when URL-related action fails
 *
 * @see https://www.php.net/manual/en/ref.url.php
 */
class UrlException extends DataException {}

/**
 * Thrown when input is out of range
 */
class ValueRangeException extends DataException {}

/**
 * Thrown when input has the wrong type
 */
class ValueTypeException extends DataException {}

/**
 * Thrown for XML-related errors
 *
 * @see https://www.php.net/manual/en/refs.xml.php
 */
class XmlException extends DataException {}

/** Base class for all database-related exceptions */
class DatabaseException extends BaseException {}

/** Thrown when a database connection fails */
class DatabaseConnectionException extends DatabaseException {}

/** Base class for query-related errors */
class DatabaseQueryException extends DatabaseException {}

/** Transaction-related exceptions */
class DatabaseTransactionException extends DatabaseException {}

/**
 * Exceptions related to the file system
 *
 * @see https://www.php.net/manual/en/ref.filesystem.php
 */
class FileSystemException extends BaseException {}

/**
 * Thrown for failure of directory-related actions.
 *
 * @see https://www.php.net/manual/en/ref.dir.php
 */
class DirectoryException extends FileSystemException {}

/**
 * Thrown for failure of file-related actions.
 *
 * @see https://www.php.net/manual/en/ref.dir.php
 */
class FileException extends FileSystemException {}

/**
 * Logic-related exceptions for failure to perform general actions
 */
class LogicException extends BaseException {}

/**
 * Thrown when operations such as function calls are done in the wrong order
 */
class OrderException extends LogicException {}

/**
 * Thrown when unable to parse a string
 */
class ParseException extends LogicException {}

/**
 * Thrown when a regex returns false when applied due to being malformed
 *
 * @see https://www.php.net/manual/en/ref.pcre.php
 */
class RegexException extends LogicException {}

/**
 * Program-related exceptions related to PHP program functions
 *
 * @see https://www.php.net/manual/en/ref.exec.php
 */
class ProgramException extends BaseException {}

/**
 * Thrown when failure to execute external command
 *
 * Throw when failure occurs in exec, passthru, shell_exec, or system.
 *
 * @see https://www.php.net/manual/en/ref.exec.php
 */
class CommandException extends ProgramException {}

/**
 * Thrown when errors occur using proc_* functions.
 *
 * @see https://www.php.net/manual/en/ref.exec.php
 */
class ProcessException extends ProgramException {}

/**
 * Service-related exceptions related to PHP or custom services
 *
 * @see https://www.php.net/manual/en/refs.remote.other.php
 */
class ServiceException extends BaseException {}

/**
 * Curl-related exceptions
 *
 * @see https://www.php.net/manual/en/book.curl.php
 */
class CurlException extends ServiceException {}

/**
 * FTP-related exceptions
 *
 * @see https://www.php.net/manual/en/book.ftp.php
 */
class FtpException extends ServiceException {}

/**
 * LDAP-related exceptions
 *
 * @see https://www.php.net/manual/en/book.ldap.php
 */
class LdapException extends ServiceException {}

/**
 * Network-related exceptions
 *
 * @see https://www.php.net/manual/en/book.network.php
 */
class NetworkException extends ServiceException {}

/**
 * Socket-related exceptions
 *
 * @see https://www.php.net/manual/en/book.sockets.php
 */
class SocketException extends ServiceException {}

/**
 * SSH2-related exceptions
 *
 * @see https://www.php.net/manual/en/book.ssh2.php
 */
class Ssh2Exception extends ServiceException {}
