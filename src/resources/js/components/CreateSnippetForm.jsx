import React, { useState, useEffect, useRef } from "react";
import CodeMirror from "@uiw/react-codemirror";
import { githubDark } from "@uiw/codemirror-theme-github";
import { FaArrowLeft, FaSearch } from "react-icons/fa";
import { AiOutlineClose } from "react-icons/ai";
import { javascript } from "@codemirror/lang-javascript";
import { php } from "@codemirror/lang-php";
import { python } from "@codemirror/lang-python";
import { xml } from "@codemirror/lang-xml";
import "C:/Snippets/src/resources/css/CreateSnippetForm.css";

const CreateSnippetForm = () => {
    const [path, setPath] = useState("");
    const [files, setFiles] = useState([]);
    const [allFiles, setAllFiles] = useState([]);
    const [selectedFile, setSelectedFile] = useState(null);
    const [searchQuery, setSearchQuery] = useState("");
    const [code, setCode] = useState("");
    const [title, setTitle] = useState("");
    const [language, setLanguage] = useState("");
    const [languageExtension, setLanguageExtension] = useState(null);

    const editorRef = useRef(null);

    const GITHUB_API_URL = "https://api.github.com/repos/Bebras1/Snippets/contents";

    const languageMap = {
        php: php(),
        js: javascript(),
        json: javascript(),
        xml: xml(),
        py: python(),
    };

    const fetchFiles = async (currentPath = "") => {
        try {
            const response = await fetch(`${GITHUB_API_URL}/${currentPath}`);
            if (!response.ok) throw new Error(`Failed to fetch files: ${response.statusText}`);
            const data = await response.json();
            setFiles(data);
            setAllFiles(data);
            setPath(currentPath);
        } catch (error) {
            console.error(error.message);
        }
    };

    const fetchFileContent = async (fileUrl, fileName) => {
        try {
            const response = await fetch(fileUrl);
            if (!response.ok) throw new Error(`Failed to fetch file content: ${response.statusText}`);
            const data = await response.text();
            setCode(data);

            const fileExtension = fileName.split(".").pop().toLowerCase();
            setLanguage(fileExtension.toUpperCase());
            setLanguageExtension(languageMap[fileExtension] || null);
        } catch (error) {
            console.error(error.message);
        }
    };

    const handleFileClick = (file) => {
        if (file.type === "dir") {
            fetchFiles(file.path);
        } else if (file.type === "file") {
            setSelectedFile(file.name);
            fetchFileContent(file.download_url, file.name);
        }
    };

    const goBack = () => {
        const parentPath = path.substring(0, path.lastIndexOf("/"));
        fetchFiles(parentPath);
    };

    const handleSearch = (e) => {
        const query = e.target.value.toLowerCase();
        setSearchQuery(query);

        if (!query) {
            setFiles(allFiles);
        } else {
            setFiles(
                allFiles.filter((file) =>
                    file.name.toLowerCase().includes(query)
                )
            );
        }
    };

    const handleCreateSnippet = async () => {
        const metaTag = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = metaTag ? metaTag.content : null;

        if (!title || !code || !language || !selectedFile) {
            console.error("Validation failed: All fields are required.");
            return;
        }

        if (!csrfToken) {
            console.error("CSRF token not found.");
            return;
        }

        try {
            const response = await fetch("/snippets", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify({
                    title,
                    code: editorRef.current?.state.doc.sliceString(
                        editorRef.current.state.selection.main.from,
                        editorRef.current.state.selection.main.to
                    ) || code,
                    language,
                    description: "",
                    file_path: `${path}/${selectedFile}`,
                }),
            });

            if (!response.ok) {
                const errorResponse = await response.json();
                console.error("Error creating snippet:", errorResponse);
                return;
            }

            console.log("Snippet created successfully.");
        } catch (error) {
            console.error("Unexpected error during snippet creation:", error.message);
        }
    };

    useEffect(() => {
        fetchFiles();
    }, []);

    return (
        <div className="snippet-container">
            {!selectedFile ? (
                <>
                    <div className="search-bar">
                        <input
                            type="text"
                            placeholder="Search files..."
                            value={searchQuery}
                            onChange={handleSearch}
                        />
                        <FaSearch className="search-icon" />
                    </div>
                    <table className="file-explorer">
                        <thead>
                            <tr>
                                <th>
                                    <div className="table-header">
                                        <span>Repo: Snippets {path && ` > ${path}`}</span>
                                        {path && (
                                            <button className="back-button" onClick={goBack}>
                                                <FaArrowLeft /> Back
                                            </button>
                                        )}
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {files.map((file) => (
                                <tr
                                    key={file.path}
                                    className="file-row"
                                    onClick={() => handleFileClick(file)}
                                >
                                    <td>
                                        {file.type === "dir" ? "📂" : "📄"} {file.name}
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </>
            ) : (
                <div>
                    <div className="code-editor-container">
                        <div className="editor-header">
                            <input
                                type="text"
                                placeholder="Snippet Title"
                                value={title}
                                onChange={(e) => setTitle(e.target.value)}
                                className="snippet-title-input"
                            />
                            <input
                                type="text"
                                placeholder="Language"
                                value={language}
                                onChange={(e) => setLanguage(e.target.value)}
                                className="snippet-language-input"
                            />
                            <AiOutlineClose
                                onClick={() => setSelectedFile(null)}
                                className="exit-button"
                            />
                        </div>
                        <CodeMirror
                            value={code}
                            height="400px"
                            theme={githubDark}
                            extensions={languageExtension ? [languageExtension] : []}
                            onUpdate={(viewUpdate) => {
                                editorRef.current = viewUpdate.view;
                            }}
                        />
                    </div>
                    <button onClick={handleCreateSnippet} className="create-snippet-btn">
                        Save Snippet
                    </button>
                </div>
            )}
        </div>
    );
};

export default CreateSnippetForm;
